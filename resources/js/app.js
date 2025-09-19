const originalWriteText = navigator.clipboard?.writeText?.bind(navigator.clipboard);
if (originalWriteText) {
  navigator.clipboard.writeText = async text => originalWriteText(text);
} else {
  navigator.clipboard = {};
  navigator.clipboard.writeText = async text => {
    return new Promise((resolve, reject) => {
      const t = document.createElement('textarea');
      t.value = text;
      t.style.position = 'fixed';
      t.style.opacity = '0';
      document.body.appendChild(t);
      t.select();
      const success = document.execCommand('copy');
      document.body.removeChild(t);
      success ? resolve() : reject(new Error('Fallback copy failed'));
    });
  }
}