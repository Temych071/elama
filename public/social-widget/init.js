(() => {
    fetch(`${window.dgSocialWidgetData.apiUrl}/assets`)
        .then((res) => res.json())
        .then((res) => {
            let head = document.querySelector('head');

            res.import.js.forEach((jsFile) => {
                let el = document.createElement('script');
                el.setAttribute('src', jsFile);
                el.setAttribute('type', 'text/javascript');
                head.append(el);
            });

            res.import.css.forEach((cssFile) => {
                let el = document.createElement('link');
                el.setAttribute('href', cssFile);
                el.setAttribute('rel', 'stylesheet');
                head.append(el);
            });
        });
})();
