import introJs from 'intro.js';

window.demoButtonClickMessage = function(event){
    event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()
}

const TOUR_STORAGE_PREFIX = 'almamia_tour_visto';

const getTourContext = () => {
    const scopeElement = document.querySelector('[data-tour-scope]');
    if (!scopeElement) {
        return null;
    }

    const scope = scopeElement.dataset.tourScope;
    const userId = document.body?.dataset?.tourUser || 'guest';
    const storageKey = `${TOUR_STORAGE_PREFIX}:${userId}:${scope}`;

    return { scopeElement, scope, userId, storageKey };
};

const buildSteps = (scopeElement) => {
    return Array.from(scopeElement.querySelectorAll('[data-tour-step]'))
        .sort((a, b) => Number(a.dataset.tourStep) - Number(b.dataset.tourStep))
        .map((element) => ({
            element,
            title: element.dataset.tourTitle || undefined,
            intro: element.dataset.tourText || '',
            position: element.dataset.tourPosition || 'bottom',
        }))
        .filter((step) => step.intro.trim() !== '');
};

const markTourAsSeen = (storageKey) => {
    localStorage.setItem(storageKey, '1');
};

const shouldRunTourAutomatically = (storageKey) => {
    return localStorage.getItem(storageKey) !== '1';
};

const startTour = ({ force = false } = {}) => {
    const context = getTourContext();
    if (!context) {
        return;
    }

    const steps = buildSteps(context.scopeElement);
    if (!steps.length) {
        return;
    }

    if (!force && !shouldRunTourAutomatically(context.storageKey)) {
        return;
    }

    const tour = introJs();
    tour.setOptions({
        steps,
        nextLabel: 'Siguiente',
        prevLabel: 'Anterior',
        doneLabel: 'Finalizar',
        skipLabel: 'Salir',
        showBullets: true,
        showProgress: true,
        disableInteraction: false,
        scrollToElement: true,
    });

    tour.oncomplete(() => markTourAsSeen(context.storageKey));
    tour.onexit(() => markTourAsSeen(context.storageKey));
    tour.start();
};

const toggleManualButton = () => {
    const button = document.querySelector('[data-tour-manual-button]');
    if (!button) {
        return;
    }

    const hasScope = !!document.querySelector('[data-tour-scope]');
    button.classList.toggle('hidden', !hasScope);
};

const setupManualButton = () => {
    const button = document.querySelector('[data-tour-manual-button]');
    if (!button || button.dataset.tourBound === '1') {
        return;
    }

    button.addEventListener('click', () => startTour({ force: true }));
    button.dataset.tourBound = '1';
};

const initTours = () => {
    toggleManualButton();
    setupManualButton();
    startTour();
};

document.addEventListener('DOMContentLoaded', initTours);
document.addEventListener('livewire:navigated', () => {
    window.setTimeout(initTours, 80);
});
