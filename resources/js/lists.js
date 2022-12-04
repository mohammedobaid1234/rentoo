let GLOBALS = {
    options: {},
    lists: {
        active: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="active"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 1, name: "مجمد"},
                    {id: 2, name: "فعال"}
                ]
            });
        },
        roles: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="roles"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/users/roles",
                formatters: {
                    option: {
                        // value: "id",
                        value: "name",
                        title: "label"
                    }
                }
            });
        },
        type_of_vendors: function (element) {
            
            if (element === undefined) {
                element = $('[data-options_source="type_of_vendors"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/type_of_vendors",
                formatters: {
                    option: {
                        title: "name"
                    }
                }
            });
        },
        vendors: function (element) {
            
            if (element === undefined) {
                element = $('[data-options_source="vendors"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/vendors",
                formatters: {
                    option: {
                        title: "company_name"
                    }
                }
            });
        },
        categories: function (element) {
            
            if (element === undefined) {
                element = $('[data-options_source="categories"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/categories",
                formatters: {
                    option: {
                        title: 'name'
                    }
                }
            });
        },
        // type_of_vendors: function (element) {
        //     if (element === undefined) {
        //         element = $('[data-options_source="type_of_vendors"]');
        //     }

        //     $(element).briskSelectOptions({
        //         options: [
        //             {id: 1, name: "شاليهات"},
        //             {id: 2, name: "سيارات"}
        //         ]
        //     });
           
        // },
        schools: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="schools"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/schools",
                ajax: true,
                formatters: {
                    option: {
                        title: "full_name"
                    }
                }
            });
        },
        exit_status: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="exit_status"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 0, name: "لم يخرج بعد"},
                    {id: 1, name: "خرج"}
                ]
            });
        },
        dayes: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="dayes"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 6, name: "السبت"},
                    {id: 0, name: "الأحد"},
                    {id: 1, name: "الاثنين"},
                    {id: 2, name: "الثلاثاء"},
                    {id: 3, name: "الأربعاء"},
                    {id: 4, name: "الخميس"},
                    {id: 5, name: "الجمعة"},
                ]
            });
        },
    }
};
