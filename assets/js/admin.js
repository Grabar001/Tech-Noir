import { Application } from '@hotwired/stimulus';

const application = Application.start();
application.register('filtre', FiltreController);

console.log('🟣 Stimulus admin.js inicialise');
window.Stimulus = application;