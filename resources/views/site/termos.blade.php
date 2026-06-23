@extends('layouts.public')

@section('title', 'Termos de Uso')
@section('meta_description', 'Termos de Uso do '.$settings->site_name.': regras para utilização do site e dos comentários.')

{{-- MODELO base. Recomenda-se revisão por um(a) advogado(a) e ajuste do e-mail de
     contato/razão social e foro antes de considerá-lo definitivo. --}}

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Termos de Uso</h1>
        <p class="mt-2 text-sm text-slate-500">Última atualização: 23 de junho de 2026</p>

        <div class="article-content mt-8">
            <p>Bem-vindo ao <strong>{{ $settings->site_name }}</strong>. Ao acessar e utilizar este site, você concorda com estes Termos de Uso. Caso não concorde, por favor não utilize o site.</p>

            <h2>1. Uso do site</h2>
            <p>O conteúdo é disponibilizado para fins informativos. Você se compromete a utilizar o site de forma lícita, sem prejudicar seu funcionamento, sua segurança ou os direitos de terceiros.</p>

            <h2>2. Comentários</h2>
            <ul>
                <li>Os comentários passam por moderação e podem ser editados ou removidos a nosso critério.</li>
                <li>É proibido publicar conteúdo ofensivo, ilegal, difamatório, spam ou que viole direitos de terceiros.</li>
                <li>Você é o único responsável pelo conteúdo que publica e pelas informações que fornece.</li>
            </ul>

            <h2>3. Propriedade intelectual</h2>
            <p>Os textos, imagens, marcas e demais materiais publicados pertencem ao <strong>{{ $settings->site_name }}</strong> ou a seus respectivos titulares. É vedada a reprodução total ou parcial sem autorização prévia, salvo citação com a devida referência e link para a fonte.</p>

            <h2>4. Conteúdo e links de terceiros</h2>
            <p>O site pode conter anúncios, links e materiais incorporados de terceiros. Não nos responsabilizamos pelo conteúdo, pelas práticas ou pelas políticas desses serviços externos.</p>

            <h2>5. Limitação de responsabilidade</h2>
            <p>Empenhamo-nos pela precisão das informações, mas não garantimos que o conteúdo esteja sempre completo, atualizado ou livre de erros. O uso das informações é de sua responsabilidade. O site é fornecido “no estado em que se encontra”, podendo ficar indisponível para manutenção.</p>

            <h2>6. Privacidade</h2>
            <p>O tratamento de dados pessoais é regido pela nossa <a href="{{ route('privacidade') }}">Política de Privacidade</a>.</p>

            <h2>7. Alterações dos termos</h2>
            <p>Podemos atualizar estes Termos a qualquer momento. As alterações passam a valer a partir da publicação nesta página.</p>

            <h2>8. Legislação e foro</h2>
            <p>Estes Termos são regidos pelas leis do Brasil. Fica eleito o foro da comarca do domicílio do responsável pelo site para dirimir eventuais controvérsias.</p>

            <h2>9. Contato</h2>
            <p>Dúvidas sobre estes Termos? Escreva para <a href="mailto:contato@dannoticias.com">contato@dannoticias.com</a>.</p>
        </div>

        <div class="mt-10 border-t border-slate-200 pt-6">
            <a href="{{ route('home') }}" class="text-sm text-sky-700 hover:underline">← Voltar para a home</a>
        </div>
    </div>
@endsection
