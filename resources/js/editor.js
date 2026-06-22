import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Youtube from '@tiptap/extension-youtube';
import Placeholder from '@tiptap/extension-placeholder';

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

async function uploadImage(file) {
    const data = new FormData();
    data.append('file', file);

    const response = await fetch('/admin/upload', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
        body: data,
    });

    if (!response.ok) {
        throw new Error('Falha no upload');
    }

    return (await response.json()).url;
}

function runCommand(editor, cmd, root) {
    const chain = editor.chain().focus();

    switch (cmd) {
        case 'bold': chain.toggleBold().run(); break;
        case 'italic': chain.toggleItalic().run(); break;
        case 'h2': chain.toggleHeading({ level: 2 }).run(); break;
        case 'h3': chain.toggleHeading({ level: 3 }).run(); break;
        case 'bulletList': chain.toggleBulletList().run(); break;
        case 'orderedList': chain.toggleOrderedList().run(); break;
        case 'blockquote': chain.toggleBlockquote().run(); break;
        case 'undo': chain.undo().run(); break;
        case 'redo': chain.redo().run(); break;
        case 'link': {
            const url = window.prompt('URL do link:');
            if (url) {
                chain.extendMarkRange('link').setLink({ href: url }).run();
            } else {
                chain.unsetLink().run();
            }
            break;
        }
        case 'image':
            root.querySelector('input[data-image-input]')?.click();
            break;
        case 'youtube': {
            const url = window.prompt('URL do vídeo do YouTube:');
            if (url) {
                editor.commands.setYoutubeVideo({ src: url });
            }
            break;
        }
    }
}

function initEditor(root) {
    const input = root.querySelector('textarea[data-editor-input]');
    const mount = root.querySelector('[data-editor-content]');

    if (!mount) {
        return;
    }

    const editor = new Editor({
        element: mount,
        editorProps: { attributes: { class: 'tiptap' } },
        extensions: [
            StarterKit,
            Link.configure({ openOnClick: false }),
            Image,
            Youtube.configure({ width: 640, height: 360, nocookie: true }),
            Placeholder.configure({ placeholder: 'Escreva a notícia...' }),
        ],
        content: input ? input.value : '',
        onUpdate({ editor }) {
            if (input) {
                input.value = editor.getHTML();
            }
        },
    });

    root.querySelectorAll('[data-cmd]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            runCommand(editor, button.dataset.cmd, root);
        });
    });

    const fileInput = root.querySelector('input[data-image-input]');
    fileInput?.addEventListener('change', async (event) => {
        const file = event.target.files[0];
        if (!file) {
            return;
        }
        try {
            const url = await uploadImage(file);
            editor.chain().focus().setImage({ src: url }).run();
        } catch {
            alert('Falha no upload da imagem.');
        }
        event.target.value = '';
    });
}

export function initEditors(root = document) {
    root.querySelectorAll('[data-editor]').forEach(initEditor);
}

/**
 * Campos de upload de imagem simples (capa, logo, favicon, banner de anúncio).
 * Estrutura esperada: container [data-image-field] com input[data-file],
 * input[data-url] (hidden, enviado no form) e img[data-preview].
 */
export function initImageFields(root = document) {
    root.querySelectorAll('[data-image-field]').forEach((field) => {
        const fileInput = field.querySelector('input[data-file]');
        const urlInput = field.querySelector('input[data-url]');
        const preview = field.querySelector('[data-preview]');

        fileInput?.addEventListener('change', async (event) => {
            const file = event.target.files[0];
            if (!file) {
                return;
            }
            try {
                const url = await uploadImage(file);
                if (urlInput) urlInput.value = url;
                if (preview) {
                    preview.src = url;
                    preview.classList.remove('hidden');
                }
            } catch {
                alert('Falha no upload da imagem.');
            }
        });
    });
}
