@extends('layouts.public')

@section('title', 'Política de Privacidade')
@section('meta_description', 'Política de Privacidade do '.$settings->site_name.': como coletamos, usamos e protegemos seus dados.')

{{-- MODELO base (LGPD/AdSense). Recomenda-se revisão por um(a) advogado(a) e ajuste
     do e-mail de contato/razão social antes de considerá-lo definitivo. --}}

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Política de Privacidade</h1>
        <p class="mt-2 text-sm text-slate-500">Última atualização: 23 de junho de 2026</p>

        <div class="article-content mt-8">
            <p>Esta Política de Privacidade descreve como o <strong>{{ $settings->site_name }}</strong> (“nós”) coleta, usa e protege as informações dos visitantes (“você”) ao utilizar este site. Ao navegar, você concorda com as práticas aqui descritas, em conformidade com a <strong>Lei Geral de Proteção de Dados (LGPD – Lei nº 13.709/2018)</strong>.</p>

            <h2>1. Dados que coletamos</h2>
            <p><strong>Dados fornecidos por você:</strong> ao enviar um comentário, coletamos o nome informado e o conteúdo da mensagem.</p>
            <p><strong>Dados coletados automaticamente:</strong> endereço IP, tipo de navegador e dispositivo, páginas acessadas e data/hora, por meio de registros (logs) e contadores de visita, para fins estatísticos e de segurança.</p>

            <h2>2. Como usamos os dados</h2>
            <ul>
                <li>Exibir e moderar comentários;</li>
                <li>Gerar estatísticas de audiência e melhorar o conteúdo;</li>
                <li>Garantir a segurança e prevenir abusos (ex.: limite de envios por IP);</li>
                <li>Cumprir obrigações legais.</li>
            </ul>

            <h2>3. Cookies</h2>
            <p>Utilizamos cookies essenciais (como o de sessão) para o funcionamento do site. Também podem ser usados cookies de terceiros para publicidade e análise (veja a seção seguinte). Você pode gerenciar ou bloquear cookies nas configurações do seu navegador.</p>

            <h2>4. Publicidade e Google AdSense</h2>
            <p>Este site pode exibir anúncios do <strong>Google AdSense</strong>. O Google e seus parceiros utilizam cookies para veicular anúncios com base em visitas anteriores a este e a outros sites. Você pode desativar a publicidade personalizada nas <a href="https://adssettings.google.com" target="_blank" rel="noopener nofollow">Configurações de anúncios do Google</a> e saber mais nas <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener nofollow">políticas de publicidade do Google</a>.</p>

            <h2>5. Compartilhamento de dados</h2>
            <p>Não vendemos seus dados. O compartilhamento ocorre apenas com prestadores que viabilizam o serviço (ex.: hospedagem, plataformas de publicidade) ou quando exigido por lei/autoridade competente.</p>

            <h2>6. Seus direitos (LGPD)</h2>
            <p>Você pode solicitar, a qualquer momento: confirmação e acesso aos seus dados; correção; anonimização ou exclusão; informação sobre compartilhamento; e revogação de consentimento. Para exercê-los, entre em contato (seção 9).</p>

            <h2>7. Retenção e segurança</h2>
            <p>Mantemos os dados apenas pelo tempo necessário às finalidades descritas ou exigências legais. Adotamos medidas técnicas e administrativas razoáveis para proteger as informações; contudo, nenhum sistema é totalmente imune a incidentes.</p>

            <h2>8. Conteúdo de terceiros</h2>
            <p>O site pode conter links e conteúdos incorporados (ex.: vídeos do YouTube). Esses serviços possuem políticas próprias, pelas quais não nos responsabilizamos.</p>

            <h2>9. Contato</h2>
            <p>Para dúvidas ou solicitações sobre seus dados, escreva para <a href="mailto:contato@dannoticias.com">contato@dannoticias.com</a>.</p>

            <h2>10. Alterações</h2>
            <p>Esta política pode ser atualizada periodicamente. A data da última revisão é indicada no topo desta página.</p>
        </div>

        <div class="mt-10 border-t border-slate-200 pt-6">
            <a href="{{ route('home') }}" class="text-sm text-sky-700 hover:underline">← Voltar para a home</a>
        </div>
    </div>
@endsection
