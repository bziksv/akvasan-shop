//Пример использования: cz_validated.run('btn_sibmit', 'group1');
//Обычное поле для проверки <input type="text" value="" data-cz-validated-type="data" data-cz-validated-group="group1" />
//Поле для проверки почты <input type="text" value="" data-cz-validated-type="email" data-cz-validated-group="group1" data-cz-validated-msg="Необходимо заполнить"  />
//Чекбокс <input type="checkbox" value="" data-cz-validated-type="checkbox" data-cz-validated-group="group1" />
// <textarea data-cz-validated-type="data" data-cz-validated-group="group1" data-cz-validated-msg="Необходимо заполнить textarea"></textarea>


//div.cz-wrap-error{position: relative;}div.cz-wrap-error p.cz-input-error{position:absolute;font-size:10px;top:0;font-style:italic;color:#F96F7F;}


//$('').mask("+7(999) 999-99-99");

;var cz_validated = (function() {
    
    var version = '1.0.2a';                     //Версия модуля
    var allElements = {};
    var forEach = Array.prototype.forEach;

    //К элементу "e" добавить класс "с"
    function addClass(e,c) {
        if (e.classList) {
            e.classList.add(c);
        }
        else {
            e.className+=' '+c;
        }
    }

    //У элемента "e" удалить класс "с"
    function removeClass(e,c) {
        if (e.classList) {
            e.classList.remove(c);
        }
        else {
            e.className=e.className.replace(new RegExp('(^|\\b)'+c.split(' ').join('|')+'(\\b|$)','gi'),' ');
        }
    }

    //Проверка номера корректности ввода email
    function testEmail(v) {
        var f = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;
        if (f.test(v)) {
            return true;
        }
        else {
            return false;
        }
    }

    //Проверка ввода чего-нибудь
    function testField(v) {
        var l = v.length;
        if (l > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    function errorContainer(e) {
        var field = e.closest('.auth-form__field');
        if (field) {
            return field;
        }
        var consent = e.closest('.order-consent');
        if (consent) {
            return consent;
        }
        return null;
    }

    //Выдача сообщения после элемента "e" об ошибке с текстом "m"
    function showMsgError(e,m) {
        clearError(e);
        addClass(e,'cz-error');
        var html = '<div class="cz-wrap-error"><p class="cz-input-error">'+m+'</p></div>';
        var container = errorContainer(e);
        if (container) {
            container.insertAdjacentHTML('beforeend', html);
        }
        else {
            e.insertAdjacentHTML('afterend', html);
        }
    }

    //Удаления сообщения об ошибке у элемента "e"
    function clearFieldFromMsgError(e) {
        clearError(e.target);
    }

    function clearError(e) {
        removeClass(e,'cz-error');
        var container = errorContainer(e);
        if (container) {
            var wrap = container.querySelector(':scope > .cz-wrap-error');
            if (wrap && wrap.parentNode) {
                wrap.parentNode.removeChild(wrap);
            }
            return;
        }
        var i = e.nextSibling;
        if (i != null && i.className=="cz-wrap-error") {
            i && i.parentNode && i.parentNode.removeChild(i);
        }
    }

    function bindGroup(group) {
        allElements[group] = document.querySelectorAll('[data-cz-validated-group="'+group+'"]');

        forEach.call(allElements[group], function(item) {
            item.addEventListener("keyup", clearFieldFromMsgError);
            item.addEventListener("change", clearFieldFromMsgError);
        });
    }

    function testFields(code) {
        var result = true;
        forEach.call(allElements[code], function(item) {
            var type = item.getAttribute("data-cz-validated-type");
            switch(type) {
                case 'data':
                {
                    if(!testField(item.value))
                    {
                        showMsgError(item,item.getAttribute("data-cz-validated-msg"));
                        result = false;
                    }
                    break;
                }
                case 'email':
                {
                    if(!testEmail(item.value))
                    {
                        showMsgError(item,item.getAttribute("data-cz-validated-msg"));
                        result = false;
                    }
                    break;
                }
                case 'phone':
                {
                    if(!/^\+7-\d{3}-\d{3}-\d{2}-\d{2}$/.test(item.value))
                    {
                        showMsgError(item,item.getAttribute("data-cz-validated-msg"));
                        result = false;
                    }
                    break;
                }
                case 'checkbox':
                {
                    if(!item.checked)
                    {
                        showMsgError(item,item.getAttribute("data-cz-validated-msg"));
                        result = false;
                    }
                    break;
                }
            }
        });
        return result;
    }

    function testFieldsBtn(event) {
        if (!testFields(this.id)) {
            event.preventDefault();
        }
    }

    return {
        run: function(group)
        {
            bindGroup(group);
            return testFields(group);
        },
        bind: function(group)
        {
            bindGroup(group);
            return true;
        },
        runBtn: function(btn, group) {
            allElements[btn] = document.querySelectorAll('[data-cz-validated-group="' + group + '"]');

            forEach.call(allElements[btn], function(item) {
                item.addEventListener("keyup", clearFieldFromMsgError);
                item.addEventListener("change", clearFieldFromMsgError);
            });

            document.getElementById(btn).addEventListener("click", testFieldsBtn);

            return true;
        }
    }
})();