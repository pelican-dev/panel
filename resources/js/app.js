(() => {
  if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') return;
  navigator.clipboard = navigator.clipboard || {};
  navigator.clipboard.writeText = async (text) =>
    new Promise((resolve, reject) => {
      const textarea = document.createElement('textarea');
      textarea.value = text;
      textarea.style.position = 'fixed';
      textarea.style.left = '-9999px';
      textarea.style.opacity = '0';
      document.body.appendChild(textarea);
      textarea.select();
      const success = document.execCommand('copy');
      document.body.removeChild(textarea);
      success ? resolve() : reject('Fallback copy failed');
    });
})();