import 'preline';

document.addEventListener('DOMContentLoaded', () => {
    window.HSStaticMethods.autoInit(); // Ensure Preline initializes on page load
});

document.addEventListener('livewire:navigated', () => {
    console.log("Livewire navigation detected! Reinitializing Preline...");
    window.HSStaticMethods.autoInit(); // Reinitialize Preline after Livewire updates
});


