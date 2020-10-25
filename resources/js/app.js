require('./bootstrap');

document.addEventListener('keyup', event => {

    if (event.keyCode == 13) {
        console.log('preventing default.....');

        event.preventDefault();

        if (event.target.id === 'tags') {
            console.log(event.target.value);

        }

        return false;
    }
});