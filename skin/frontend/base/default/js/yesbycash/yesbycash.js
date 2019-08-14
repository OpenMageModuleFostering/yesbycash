if (typeof Yesbycash == 'undefined') {
    var Yesbycash = {};
}
var Yesbycash = Class.create();
Yesbycash.prototype = {
    initialize: function () {
        this.locations = [];
        this.tabOutlets = [];
        this.url = '';
        this.selectaTxtOff = '';
        this.selectaTxtOn = '';
        setTimeout(function () {
            yesbycash.testForVersion();
        }, 200);
    },
    testForVersion: function () {
        window.tsi.maps.YBC.onReady(function (Ybc) {
            if (!Ybc.isCompatible())
            {
                var head;
                var script;
                head = $$('head')[0];
                if (head)
                {
                    script = new Element('script', {type: 'text/javascript', src: 'https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=AIzaSyDuvsiEXzvlMP0GmbkgYfbOYM-TPp8GwQ0&sensor=false&v=3.17&callback=initialize'});
                    head.appendChild(script);
                }
            }
        });
    },
    bindMap: function (postcode) {
        window.tsi.maps.YBC.onReady(function (Ybc) {
            if (Ybc.isCompatible())
            {
                $('ybc_outlet_fields').setStyle({display: 'none'});
                $$('.ybc_outlet_found').first().setStyle({display: 'none'});
                $$('.outletsList').first().setStyle({display: 'none'});
                $('ybc_no_outlet').setStyle({display: 'none'});
                setTimeout(function () {
                    new Ybc.map('#carte_YBC', {
                        defaultAddress: "'" + postcode + "'",
                        geoloc: false,
                        onConfirmOutlet: function (outlet_id, outlet) {
                            if (null == outlet_id)
                            {
                                $('ybc_outlet_selecta').value = '';
                                yesbycash.OutletSelection();
                            }
                            else
                            {
                                $('ybc_outlet_selecta').value = outlet_id;
                                yesbycash.OutletSelection();
                            }
                        }
                    });
                }, 100);
            }
            else {
                setTimeout(function () {
                    $('carte_YBC_wrapper').setStyle({width: '70%'});
                    $('carte_YBC_wrapper').setStyle({float: 'left'});
                    $('carte_YBC').setStyle({height: '400px'});
                    yesbycash.setMap();
                }, 200);
            }
        });
    },
    clickForMap: function () {
        var loader = $('ybc_loader_search');
        loader.show();
        loader.setStyle({display: 'inline-block'});
        new Ajax.Updater('part-for-ajax', this.url, {
            method: 'get',
            evalScripts: true,
            parameters: {
                postcode: $('ybc_map_department').getValue(),
                country_id: $('ybc_map_country').getValue()
            },
            onSuccess: function () {
                loader.hide();
            }
        });
    },
    setMap: function () {
        this.handleOutletSelection();
        for (var i = 0; i < this.tabOutlets.length; i++) {
            this.locations[i] = new Array();
            this.locations[i][0] = '<center><b>' + this.tabOutlets[i][0] + '<br />' + this.tabOutlets[i][1] + '</b><br />' + this.tabOutlets[i][2] + '<br />' + this.tabOutlets[i][3] + '</center>';
            this.locations[i][1] = this.tabOutlets[i][4];
            this.locations[i][2] = this.tabOutlets[i][5];
        }

        var map = new google.maps.Map($('carte_YBC'), {
            zoom: 14,
            center: new google.maps.LatLng(this.tabOutlets[0][4], this.tabOutlets[0][5]),
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.SMALL,
                position: google.maps.ControlPosition.TOP_RIGHT
            },
            scaleControl: true,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var marker, i;
        for (i = 0; i < this.locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(this.locations[i][1], this.locations[i][2]),
                map: map
            });
            var infowindow = new google.maps.InfoWindow({
            });
            var locations = this.locations;
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infowindow.setContent('<div style="width:200px">' + locations[i][0] + '</div>');
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }

    },
    handleOutletSelection: function () {
        var arrBtns = $$('.ybc_outlet_btn');
        var hidden = $('ybc_outlet_selecta');
        var prevId = '';
        var outletId = '';
        for (var k = 0; k < arrBtns.length; k++) {
            var btn = $(arrBtns[k].id);
            var selectaTxtOff = this.selectaTxtOff;
            var selectaTxtOn = this.selectaTxtOn;
            btn.onclick = function (e) {
                $('ybc_outlet').setStyle({display: 'block'});
                e.preventDefault();
                var btnId = this.id;
                outletId = btnId.substr(4);
                hidden.value = outletId;
                if (prevId !== '') {
                    $(prevId).innerHTML = selectaTxtOff;
                    $(prevId).className = $(prevId).className.replace(/(?:^|\s)ybc_outlet_selected(?!\S)/, '');
                }
                $(btnId).innerHTML = selectaTxtOn;
                $(btnId).className += ' ybc_outlet_selected';
                prevId = btnId;
            }
        }
    },
    handleNoOutletSelection: function () {
        $('ybc_loader_noselect').show();
        $('ybc_loader_noselect').setStyle({display: 'block'});
        $('ybc_outlet_selecta').value = '';
        payment.save();
    },
    OutletSelection: function () {
        payment.save();
    },
}

Validation.addMethods({
    validate: function () {
        var result = false;
        var useTitles = this.options.useTitles;
        var callback = this.options.onElementValidate;
        try {
            if (this.form.id == 'co-payment-form') {
                result = true;
            } else {
                if (this.options.stopOnFirst) {
                    result = Form.getElements(this.form).all(function (elm) {
                        if (elm.hasClassName('local-validation') && !this.isElementInForm(elm, this.form)) {
                            return true;
                        }
                        return Validation.validate(elm, {useTitle: useTitles, onElementValidate: callback});
                    }, this);
                } else {
                    result = Form.getElements(this.form).collect(function (elm) {
                        if (elm.hasClassName('local-validation')) {
                            return true;
                        }
                        return Validation.validate(elm, {useTitle: useTitles, onElementValidate: callback});
                    }, this).all();
                }
            }
        } catch (e) {
        }
        if (!result && this.options.focusOnError) {
            try {
                Form.getElements(this.form).findAll(function (elm) {
                    return $(elm).hasClassName('validation-failed')
                }).first().focus()
            }
            catch (e) {
            }
        }
        this.options.onFormValidate(result, this.form);
        return result;
    }
});

yesbycash = new Yesbycash();