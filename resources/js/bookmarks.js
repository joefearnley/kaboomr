const createBookmarkForm = document.querySelector('#create-bookmark-form');
createBookmarkForm.addEventListener('submit', event => {
    event.preventDefault();

    const createBookmarkForm = document.querySelector('#create-bookmark-form');
    var formData = new FormData(createBookmarkForm);

    // gather up the tags...
    let inputTags = [];
    document.querySelectorAll('.tags-input > span')
        .forEach(el => {
            inputTags.push(el.textContent.trim());
            for (let i = 0; i < inputTags.length; i++) {
                formData.append('tags[]', inputTags[i]);
            }
        });

        return false;

    // createBookmarkForm.submit();
});

const addTagButton = document.querySelector('#add-tag-button');
const addTagInput = document.querySelector('#add-tag-input');
const tagInputs = document.querySelector('.tags-input');

addTagButton.addEventListener('click', event => {
    event.preventDefault();
    const newTag = addTagInput.value;

    if (newTag.trim() === '') {
        return false;
    }

    const newTagMarkup = `
        <span class="badge badge-pill badge-primary mr-2 mt-2 tag-input">
            ${addTagInput.value}
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg" onclick="this.parentElement.remove();">
                <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </span>
    `;

    tagInputs.innerHTML += newTagMarkup;
    addTagInput.value = '';
});
