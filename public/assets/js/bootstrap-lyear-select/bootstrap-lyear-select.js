/**
 * 简单的select美化插件,支持搜索
 * yinqi<3331653644@qq.com>
 */
;(function($, window, document, undefined) {
    
    var lyearSelect = function(ele, opt) {
        this.$element = ele,
        this.defaults = {
            'width': '',             // 自定义长度
            'placeholder': '请选择',  // 自定义提示文字
            'search': false,         // 是否可以输入搜索
        },
        this.configs = $.extend({}, this.defaults, opt);
        if ("undefined" == typeof (bootstrap.Tooltip.VERSION)) {
            this.bsVersion = "3";
        } else {
            if (/4\.[0-9]\.[0-9]/.test(bootstrap.Tooltip.VERSION)) {
                this.bsVersion = "4";
            } else if (/5\.[0-9]\.[0-9]/.test(bootstrap.Tooltip.VERSION)) {
                this.bsVersion = "5";
            } else {
                this.bsVersion = "3";
            }
        }
    }
    
    lyearSelect.prototype = {
        init: function() {
            var $thisLyearSelect = this;
            this.$element.each(function() {
                $thisLyearSelect.createSelect($(this));
            });
        },
        createSelect: function($select) {
            var $options  = $select.find('option');
            var $selected = $select.find('option:selected');
            var $configs  = this.configs;
            var bsVersion = this.bsVersion;
            $select.hide();
            
            var $selectHtml = '<input type="text" class="form-control" value="' + $selected.text() + '" '+ ($configs.search ? '' : 'readonly') +' placeholder="' + $configs.placeholder + '" ' + (bsVersion == 5 ? 'data-bs-toggle' : 'data-toggle') + '="dropdown" />';
          
            var $divObj = $('<div></div>');
            
            $select.after(
                $divObj.addClass('lyear-select').addClass($select.attr('disabled') ? 'disabled' : '').html($selectHtml)
            );
            $configs.width && $divObj.css('width', $configs.width);
            
            var $dropdown = $select.next('.lyear-select');
            if ($dropdown.hasClass('disabled')) {
                $dropdown.find('.form-control').prop('readonly', 'true');
                return false;
            } else {
                $dropdown.append('<ul class="dropdown-menu"></ul><i class="mdi mdi-menu-down">');
            }
            
            $options.each(function() {
                var $option = $(this);
                
                if ($option.text() == '') $option.text($configs.placeholder);

                if (bsVersion == 3) {
                    $dropdown.find('ul').append(
                        $('<li></li>').attr('data-value', $option.val())
                        .addClass(($option.is(':selected') ? 'selected' : '') + 
                                  ($option.is(':disabled') ? 'disabled' : ''))
                        .append($('<a></a>').html($option.text()))
                    );
                } else {
                    $dropdown.find('ul').append(
                        $('<li></li>').attr('data-value', $option.val())
                        .addClass(($option.is(':selected') ? 'selected' : '') + 
                                  ($option.is(':disabled') ? 'disabled' : ''))
                        .append($('<a class="dropdown-item"></a>').html($option.text()))
                    );
                }
                
            });
            
            // 选项点击事件
            $(document).off('click', '.lyear-select li:not(.disabled)').on('click', '.lyear-select li:not(.disabled)', function(event) {
                var $option = $(this);
                var $lyearSelect = $option.closest('.lyear-select');
                
                $lyearSelect.find('.selected').removeClass('selected');
                $option.addClass('selected');
                
                $lyearSelect.find('input.form-control').val($option.text());
                $lyearSelect.prev('select').val($option.data('value')).trigger('change');
            });
            
            this.configs.search && $('.lyear-select .form-control').focus(function() {
                var $this = $(this);
                var words = $this.val().replace(' ', '');
                $this.timer = setInterval(function() {
                    var owords = $this.val().replace(' ', '');
                    
                    if (owords != words) {
                        words = owords;
                        if (owords == '') {
                            $this.parent('.lyear-select').find('ul.dropdown-menu').children('li').show();
                        } else {
                            $this.parent('.lyear-select').find('ul.dropdown-menu').children('li').hide();
                            
                            $this.parent('.lyear-select').find('ul.dropdown-menu li a').filter(':Contains(' + owords + ')').parent('li').show();
                        }
                    }
                }, 30);
                
                var h_close = function() {
                    clearInterval($this.timer);
                    $this.off('blur');
                    $this.off('keydown');
                };
                
                $this.on('blur', h_close);
            });
        },
    }

    $.fn.lyearSelect = function(options) {
        var obj = new lyearSelect(this, options);
        
        return obj.init();
    }
})(jQuery, window, document);