<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
 
        <title>{{ $title ?? 'DCMania' }}</title>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        
        @livewireStyles
    </head>
    <body class="bg-slate-200 dark:bg-slate-700">
    @livewire('partials.navbar')'

        <main>
        {{ $slot }}
         </main>   

     @livewire('partials.footer')'

        @livewireScripts
        

        
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('swal:success', (params) => {
                    Swal.fire({
                        icon: 'success',
                        title: params.title || 'Success!',
                        text: params.text || 'Product added to Cart Successfully.',
                        timer: params.timer || 3000,
                        showConfirmButton: false,
                        toast: false, // Set to false to display a full alert (not a small toast)
                        position: 'center' // Position the alert in the center
                    });
                });
            });
        </script>

    </body>
</html>
