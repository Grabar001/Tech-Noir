import { Application } from '@hotwired/stimulus';

const application = Application.start();
application.register('filtre', FiltreController);

console.log('🟣 Stimulus admin.js инициализирован');
window.Stimulus = application;