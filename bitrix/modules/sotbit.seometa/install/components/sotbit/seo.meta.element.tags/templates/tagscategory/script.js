;(function () {
    CategoryTags = function (params) {
        this.tagsWrapper = params.tagsWrapper;
        this.showMoreButton = params.showMoreButton;
        this.categoryTags = params.categoryTags;
        this.hideTag = params.hideTag;
        this.expanded = params.expanded;
        this.init();
    };

    CategoryTags.prototype.init = function () {
        const tagsWrapper = document.getElementById(this.tagsWrapper);
        const showMoreButton = document.getElementById(this.showMoreButton);
        const hiddenTags = document.querySelectorAll(this.hideTag);
        const categoryTags = document.querySelectorAll(this.categoryTags);
        let startHeight = 0;

        if (tagsWrapper) {
            tagsWrapper.style.height = startHeight = tagsWrapper.offsetHeight + 'px';
        }

        if(showMoreButton) {
            showMoreButton.addEventListener('click', async () => {
                tagsWrapper.classList.toggle(this.expanded);
                if (tagsWrapper.classList.contains(this.expanded)) {
                    await hiddenTags.forEach(tag => tag.classList.remove('hide-tag'));
                    let height = 0;
                    await categoryTags.forEach((category) => {
                        height += category.offsetHeight;
                    });
                    showMoreButton.textContent = BX.message('less_btn');
                    tagsWrapper.style.height = height + 'px';
                } else {
                    showMoreButton.textContent = BX.message('more_btn');
                    tagsWrapper.style.height = startHeight;
                    setTimeout(() => {
                        hiddenTags.forEach(tag => tag.classList.add('hide-tag'));
                    }, 100)
                }
            });
        }
    };
})();