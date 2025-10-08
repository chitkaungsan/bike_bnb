export function openSocialPopup(url) {
  return new Promise((resolve, reject) => {
    const width = 600, height = 700;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    const popup = window.open(url, 'oauth', `width=${width},height=${height},left=${left},top=${top}`);

    if (!popup) return reject(new Error('Popup blocked'));

    function receiveMessage(e) {
      // validate origin
      if (e.origin !== 'http://localhost:5173') return; // change for prod
      const data = e.data;
      window.removeEventListener('message', receiveMessage);
      if (data && data.token) resolve(data);
      else reject(new Error('No token received'));
    }
    window.addEventListener('message', receiveMessage);

    // optional: fallback in case popup closed without message
    const checkClosed = setInterval(() => {
      if (popup.closed) {
        clearInterval(checkClosed);
        window.removeEventListener('message', receiveMessage);
        reject(new Error('Popup closed'));
      }
    }, 500);
  });
}