document.addEventListener('DOMContentLoaded', function () {
  const initDbScript = () => {
    const isLocalMode = window.location.protocol === 'http:';
    const isProdMode = !window.location.hostname.endsWith('webflow.io') && !isLocalMode;

    const SCRIPT_BASE_URL = isLocalMode ? window.location.origin : 'https://cdn.digitalbutlers.me/projects/DB-global-leathers-digitalbutlers';

    const script = document.createElement('script');
    const mode = isProdMode ? 'prod' : isLocalMode ? 'src' : 'dev';

    const fileName = isLocalMode ? 'index.ts' : 'index.js';

    script.src = [SCRIPT_BASE_URL, mode, fileName].join('/');
    script.type = 'module'

    document.body.appendChild(script);
  }

  initDbScript();
});
