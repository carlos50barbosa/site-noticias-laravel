<?php

return [
    /*
     | Disco usado para uploads de imagens (capa, logo, favicon, anúncios).
     | "public" (padrão) grava em storage/app/public (servido via storage:link);
     | "s3" envia para um bucket S3-compatível (AWS, R2, Spaces, MinIO).
     */
    'disk' => env('UPLOAD_DISK', 'public'),

    // Limite de tamanho por imagem (KB) e tipos aceitos.
    'max_kb' => (int) env('UPLOAD_MAX_KB', 5120),
    'mimes' => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'],
];
