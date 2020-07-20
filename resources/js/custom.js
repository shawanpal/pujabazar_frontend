function trimByWord(sentence) {
    var result = sentence;
    var resultArray = result.split(" ");
    if (resultArray.length > 10) {
        resultArray = resultArray.slice(0, 10);
        result = resultArray.join(" ") + "...";
    }
    return result;
}

function showItems(id, action, page_no) {
    let leng = $("#" + id).find("th").length;
    let prev = "";
    let nxt = "";

    $("#" + id + "> tbody").html(
        '<tr><td class="text-center" colspan="' +
        leng +
        '"><span class="spinner-grow spinner-grow text-dark" role="status"><span class="sr-only">Loading...</span></span></td></tr>'
    );
    axios
        .post(action + page_no, {
            page_no: page_no,
            length: leng
        })
        .then(res => {
            $("#" + id + "> tbody").html(res.data.table_body);
            if (res.data.pagination.prev_page_url !== null) {
                prev =
                    '<li class="page-item active"><button class="page-link" value="' +
                    res.data.pagination.prev_page_url.split("page=")[1] +
                    '"><i class="fa fa-angle-double-left"></i> Previous</button></li>';
            } else {
                prev =
                    '<li class="page-item"><button class="page-link" disabled><i class="fa fa-angle-double-left"></i> Previous</button></li>';
            }
            if (res.data.pagination.next_page_url !== null) {
                nxt =
                    '<li class="page-item active"><button class="page-link" value="' +
                    res.data.pagination.next_page_url.split("page=")[1] +
                    '">Next <i class="fa fa-angle-double-right"></i></button></li>';
            } else {
                nxt =
                    '<li class="page-item"><button class="page-link" disabled>Next <i class="fa fa-angle-double-right"></i></button></li>';
            }
            $(".pagination").html(
                prev +
                '<li class="page-item">&nbsp;&nbsp;&nbsp;&nbsp;</li>' +
                nxt
            );
            // console.log(res.data.table_body);
            // console.log(res.data.pagination.current_page);
            // console.log(res.data.pagination.first_page_url);
            // console.log(res.data.pagination.from);
            // console.log(res.data.pagination.next_page_url);
            // console.log(res.data.pagination.path);
            // console.log(res.data.pagination.per_page);
            // console.log(res.data.pagination.prev_page_url);
        })
        .catch(err => {
            console.log(err);
        });
}

function seachItems(id, action, content, page_no) {
    let leng = $("#" + id).find("th").length;
    let prev = "";
    let nxt = "";

    $("#" + id + "> tbody").html(
        '<tr><td class="text-center" colspan="' +
        leng +
        '"><span class="spinner-grow spinner-grow text-dark" role="status"><span class="sr-only">Loading...</span></span></td></tr>'
    );
    axios
        .post(action + page_no, {
            page_no: page_no,
            content: content,
            length: leng
        })
        .then(res => {
            $("#" + id + "> tbody").html(res.data.table_body);
            if (res.data.pagination.prev_page_url !== null) {
                prev =
                    '<li class="page-item active"><button class="page-link" value="' +
                    res.data.pagination.prev_page_url.split("page=")[1] +
                    '"><i class="fa fa-angle-double-left"></i> Previous</button></li>';
            } else {
                prev =
                    '<li class="page-item"><button class="page-link" disabled><i class="fa fa-angle-double-left"></i> Previous</button></li>';
            }
            if (res.data.pagination.next_page_url !== null) {
                nxt =
                    '<li class="page-item active"><button class="page-link" value="' +
                    res.data.pagination.next_page_url.split("page=")[1] +
                    '">Next <i class="fa fa-angle-double-right"></i></button></li>';
            } else {
                nxt =
                    '<li class="page-item"><button class="page-link" disabled>Next <i class="fa fa-angle-double-right"></i></button></li>';
            }
            $(".pagination").html(
                prev +
                '<li class="page-item">&nbsp;&nbsp;&nbsp;&nbsp;</li>' +
                nxt
            );
            // console.log(res.data.table_body);
            // console.log(res.data.pagination.current_page);
            // console.log(res.data.pagination.first_page_url);
            // console.log(res.data.pagination.from);
            // console.log(res.data.pagination.next_page_url);
            // console.log(res.data.pagination.path);
            // console.log(res.data.pagination.per_page);
            // console.log(res.data.pagination.prev_page_url);
        })
        .catch(err => {
            console.log(err);
        });
}

$(document).ready(function () {
    let current = location.pathname;
    // console.log(current);

    if (current == "/admin/product") {
        showItems("all-product-table", "showProduct?page=", 1);
        $(document).on("click", ".page-link", function () {
            showItems("all-product-table", "showProduct?page=", $(this).val());
        });

        $(document).on("click", "#add-product", function () {
            $("#add-product").html('<span class="spinner-grow spinner-grow text-dark" role="status"><span class="sr-only">Loading...</span></span>');
            axios
                .get("product/create")
                .then(res => {
                    $(".modal-title").html(res.data.title);
                    $("#product-modal > div > div > form").attr("id", "insert-product");
                    $(".modal-body").html(res.data.body);
                    $("#btn-sbm").html(res.data.button_text);
                    $("#btn-sbm").removeClass("d-none");
                    $("#form-error").addClass("d-none");
                    $("#add-product").html('All Products');
                    $(".multipleSelect").fastselect();
                    $("#product-modal").modal("show");
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        $(document).on("submit", "#insert-product", function (event) {
            event.preventDefault();

            $("#form-error").addClass("d-none");
            $("#form-error > div > span").html("");
            let data = new FormData(event.target);
            let txt = $("#btn-sbm").html();

            $("#btn-sbm").html(
                '<span class="spinner-grow spinner-grow-sm text-dark" role="status"><span class="sr-only">Loading...</span></span>'
            );
            axios.post("product", data)
                .then(res => {
                    $("#btn-sbm").html(txt);
                    if (res.data.msg) {
                        showItems("all-product-table", "showProduct?page=", 1);
                        $("#product-modal").modal("hide");
                        $("form").find("input[type=text], input[type=file], select, textarea").val("");
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: res.data.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        $("#form-error").removeClass("d-none");
                        var errorString = '<ul>';
                        $.each(res.data.errors, function (key, value) {
                            errorString += '<li>' + value + '</li>';
                        });
                        errorString += '</ul>';
                        $("#form-error > div > span").html(errorString);
                    }
                })
                .catch(err => {
                    console.log(err);
                    $("#btn-sbm").html(txt);
                });
        });

        $(document).on("click", ".edit_product", function () {
            let str = $(this).val().split('-');
            $("input[name=page]").remove();
            axios
                .get("product/" + str[1] + "/edit")
                .then(res => {
                    $(".modal-title").html(res.data.title);
                    $("<input>").attr("type", "hidden").attr("name", "page").attr("value", str[0]).appendTo("form");
                    $("#product-modal > div > div > form").attr("id", "update-product");
                    $(".modal-body").html(res.data.body);
                    $("#btn-sbm").html(res.data.button_text);
                    $("#btn-sbm").removeClass("d-none");
                    $("#form-error").addClass("d-none");
                    $("#add-product").html('All Products');
                    $(".multipleSelect").fastselect();
                    $("#product-modal").modal("show");
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        $(document).on("submit", "#update-product", function (event) {
            event.preventDefault();

            $("#form-error").addClass("d-none");
            $("#form-error > div > span").html("");
            let data = new FormData(event.target);
            data.append("_method", "PUT");
            let txt = $("#btn-sbm").html();

            $("#btn-sbm").html(
                '<span class="spinner-grow spinner-grow-sm text-dark" role="status"><span class="sr-only">Loading...</span></span>'
            );
            axios
                .post("product/" + data.get("id"), data)
                .then(res => {
                    $("#btn-sbm").html(txt);
                    if (res.data.msg) {
                        showItems("all-product-table", "showProduct?page=", $("input[name=page]").val());
                        $("#product-modal").modal("hide");
                        $("form").find("input[type=text], input[type=file], select, textarea").val("");
                        setTimeout(function () { }, 3000);
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: res.data.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });

                    } else {
                        $("#form-error").removeClass("d-none");
                        var errorString = '<ul>';
                        $.each(res.data.errors, function (key, value) {
                            errorString += '<li>' + value + '</li>';
                        });
                        errorString += '</ul>';
                        $("#form-error > div > span").html(errorString);
                    }
                })
                .catch(err => {
                    console.log(err);
                    $("#btn-sbm").html(txt);
                });
        });

        $(document).on("click", ".delete_product", function (event) {
            let str = $(this).val().split('-');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    axios
                        .delete("product/" + str[1])
                        .then(res => {
                            showItems("all-product-table", "showProduct?page=", str[0]);
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
            });
        });

        $(document).on("keyup", "#pro-search", function () {
            let src = $(this).val().toLowerCase();
            if (src == '') {
                showItems("all-product-table", "showProduct?page=", 1);
            } else {
                seachItems("all-product-table", "searchProduct?page=", src, 1);
            }
        });

    }

    if (current == "/admin/package") {
        showItems("all-package-table", "showPackage?page=", 1);
        $(document).on("click", ".page-link", function () {
            showItems("all-package-table", "showPackage?page=", $(this).val());
        });

        $(document).on("click", "#add-package", function () {
            $("#add-package").html('<span class="spinner-grow spinner-grow text-dark" role="status"><span class="sr-only">Loading...</span></span>');
            axios
                .get("package/create")
                .then(res => {
                    $(".modal-title").html(res.data.title);
                    $("#package-modal > div > div > form").attr("id", "insert-package");
                    $(".modal-body").html(res.data.body);
                    $("#btn-sbm").html(res.data.button_text);
                    $("#btn-sbm").removeClass("d-none");
                    $("#form-error").addClass("d-none");
                    $("#add-package").html('Add Package');
                    $(".multipleSelect").fastselect({ placeholder: 'Choose State' });
                    $("#package-modal").modal("show");
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        $(document).on("submit", "#insert-package", function (event) {
            event.preventDefault();

            $("#form-error").addClass("d-none");
            $("#form-error > div > span").html("");
            let data = new FormData(event.target);
            let txt = $("#btn-sbm").html();

            $("#btn-sbm").html(
                '<span class="spinner-grow spinner-grow-sm text-dark" role="status"><span class="sr-only">Loading...</span></span>'
            );
            axios.post("package", data)
                .then(res => {
                    $("#btn-sbm").html(txt);
                    if (res.data.msg) {
                        showItems("all-package-table", "showPackage?page=", 1);
                        $("#package-modal").modal("hide");
                        $("form").find("input[type=text], input[type=file], select, textarea").val("");
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: res.data.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        $("#form-error").removeClass("d-none");
                        var errorString = '<ul>';
                        $.each(res.data.errors, function (key, value) {
                            errorString += '<li>' + value + '</li>';
                        });
                        errorString += '</ul>';
                        $("#form-error > div > span").html(errorString);
                    }
                })
                .catch(err => {
                    console.log(err);
                    $("#btn-sbm").html(txt);
                });
        });

        $(document).on("click", ".edit-package", function () {
            let str = $(this).val().split('-');
            $("input[name=page]").remove();
            axios
                .get("package/" + str[1] + "/edit")
                .then(res => {
                    $(".modal-title").html(res.data.title);
                    $("<input>").attr("type", "hidden").attr("name", "page").attr("value", str[0]).appendTo("form");
                    $("#package-modal > div > div > form").attr("id", "update-package");
                    $(".modal-body").html(res.data.body);
                    $("#btn-sbm").html(res.data.button_text);
                    $("#btn-sbm").removeClass("d-none");
                    $("#form-error").addClass("d-none");
                    $(".multipleSelect").fastselect();
                    $("#package-modal").modal("show");
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        $(document).on("submit", "#update-package", function (event) {
            event.preventDefault();

            $("#form-error").addClass("d-none");
            $("#form-error > div > span").html("");
            let data = new FormData(event.target);
            data.append("_method", "PUT");
            let txt = $("#btn-sbm").html();

            $("#btn-sbm").html(
                '<span class="spinner-grow spinner-grow-sm text-dark" role="status"><span class="sr-only">Loading...</span></span>'
            );
            axios
                .post("package/" + data.get("id"), data)
                .then(res => {
                    $("#btn-sbm").html(txt);
                    if (res.data.msg) {
                        showItems("all-package-table", "showPackage?page=", $("input[name=page]").val());
                        $("#package-modal").modal("hide");
                        $("form").find("input[type=text], input[type=file], select, textarea").val("");
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: res.data.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });

                    } else {
                        $("#form-error").removeClass("d-none");
                        var errorString = '<ul>';
                        $.each(res.data.errors, function (key, value) {
                            errorString += '<li>' + value + '</li>';
                        });
                        errorString += '</ul>';
                        $("#form-error > div > span").html(errorString);
                    }
                })
                .catch(err => {
                    console.log(err);
                    $("#btn-sbm").html(txt);
                });
        });

        $(document).on("click", ".delete-package", function (event) {
            let str = $(this).val().split('-');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    axios
                        .delete("package/" + str[1])
                        .then(res => {
                            showItems("all-package-table", "showPackage?page=", str[0]);
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
            });
        });


        $(document).on("click", "#add-more-pac-pro", function (e) {
            var listQly = [];
            var idx = 0;
            var len = $("#pac-pro-tbl > tbody").children("tr").length;
            var last_qly = $("#pac-pro-tbl > tbody").children("tr:last").prev().children("td").eq(0).children("select").val();

            if (len > 0 && !last_qly) {
                Swal.fire({
                    icon: 'error',
                    title: 'At First Set Qualaty',
                });
                return false;
            }

            $.each($("#pac-pro-tbl > tbody").children("tr"), function (index, subcatObj) {
                if (index == 0 || idx == 2) {
                    var lent = $("#pac-pro-tbl > tbody").children("tr").eq(index).children("td").eq(0).children("select").val();
                    listQly.push(lent);
                    idx = 0;
                }

                idx++;
            });

            var data = new FormData();
            data.append("total_itm_row", $("#total-itm-row").html());
            data.append("_token", $("input[name=_token]").val());
            data.append("listQly", listQly);

            axios.post("addMorProRow", data)
                .then(res => {
                    $("#total-itm-row").html(res.data.totalRow);
                    $("#pac-pro-tbl > tbody").append(res.data.more);
                    $(".multipleSelect").fastselect({ placeholder: 'Choose Items' });
                    let nm = $("#pac-pro-tbl > tbody").children("tr:last").prev().prev().prev().children("td").eq(0).children("select").attr('name');
                    let v = $("#pac-pro-tbl > tbody").children("tr:last").prev().prev().prev().children("td").eq(0).children("select").val();
                    let app = $("#pac-pro-tbl > tbody").children("tr:last").prev().prev().prev().children("td").eq(0);
                    $("<input>").attr("type", "hidden").attr("name", nm).attr("value", v).appendTo(app);
                    $("#pac-pro-tbl > tbody").children("tr:last").prev().prev().prev().children("td").eq(0).children("select").attr('disabled', true);
                })
                .catch(err => {
                    console.log(err);
                });

        });

        $(document).on("click", ".remove-pec-pro", function (event) {
            var value = parseInt($("#total-itm-row").html()) - 1;
            let id = $(this).closest("tr").children("td").eq(0).children("div").children("select").attr('id');

            var data = new FormData();
            $.each($("#" + id + " option:selected"), function () {
                data.append("id[]", $(this).val());
            });
            data.append("action", 'remove');
            data.append("total", $("#pro_tot_pri").html());
            data.append("_token", $("input[name=_token]").val());

            axios.post("showPrices", data)
                .then(res => {
                    $("#pro_tot_pri").html(res.data.proPriTot);
                    $("#total-itm-row").html(value);
                    $(this).closest("tr").prev().remove();
                    $(this).closest("tr").remove();
                })
                .catch(err => {
                    console.log(err);
                });
        });

        $(document).on("change", 'select[id^="package_product"]', function (event) {
            var id = $(this).attr('id');

            var data = new FormData();
            $.each($("#" + id + " option:selected"), function () {
                data.append("id[]", $(this).val());
            });
            $.each($('select[id^="package_product"] option:selected'), function () {
                data.append("all_id[]", $(this).val());
            });
            data.append("action", 'add');
            data.append("_token", $("input[name=_token]").val());


            axios.post("showPrices", data)
                .then(res => {
                    $("#pro_pri" + id.match(/\d+/)).html(res.data.proPrice);
                    $("#pro_tot_pri").html(res.data.proPriTot);
                })
                .catch(err => {
                    console.log(err);
                });
        });

        $(document).on("keyup", "#pac-search", function () {
            let src = $(this).val().toLowerCase();
            if (src == '') {
                showItems("all-package-table", "showPackage?page=", 1);
            } else {
                seachItems("all-package-table", "searchPackage?page=", src, 1);
            }
        });
    }

});

$(document).on("keyup", "#myInput", function () {
    let value = $(this).val().toLowerCase();
    console.log(value);

    $("#all_order tr, #all_product tr, #all_package tr").filter(function () {
        $(this).toggle(
            $(this).text().toLowerCase().indexOf(value) > -1
        );
    });
});



$(document).on("change", "#size_wet", function () {
    let sw = $(this).val();

    if (sw == "size") {
        $("#pro_more_desc > thead > tr").children("th").eq(0).html("Size");
    } else if (sw == "weigth") {
        $("#pro_more_desc > thead > tr").children("th").eq(0).html("Waight");
    } else {
        $("#pro_more_desc > thead > tr").children("th").eq(0).html("Size/Waight");
    }

    $("#pro_more_desc > tbody").empty();
});

$(document).on("click", "#pro_desc", function () {
    var sw = $("#size_wet").val();

    if (sw !== "") {
        axios
            .get("/pro/" + sw)
            .then(res => {
                $("#pro_more_desc > tbody").append(res.data.tr);
            })
            .catch(function (error) {
                console.log(error);
            });
    } else {
        alert("Size or Waight?");
    }
});

$(document).on("click", ".remove-des", function (event) {
    $(this).closest("tr").remove();
});

$(document).on("click", "#add-more-attr", function (e) {
    if (parseInt($("#total-attribute").html()) != 0) {
        // console.log($('#total-attribute').html())
    }
    if (!$("#category_id").val() && !$("#sub_category_id").val()) {
        Swal.fire({
            icon: 'error',
            title: 'Select Category and Subcategory',
        });
        return false;
    }
    var listAtt = [];
    // var rv = true;
    var len = $("#moreAttribute > tbody").children("tr").length;
    var last = $("#moreAttribute > tbody").children("tr:last").children("td").eq(0).children("select").val();

    if (len > 0 && !last) {
        Swal.fire({
            icon: 'error',
            title: 'Set Previous Attribute First',
        });
        return false;
    }
    $.each($("#moreAttribute > tbody").children("tr"), function (index, subcatObj) {
        var lent = $("#moreAttribute > tbody").children("tr").eq(index).children("td").eq(0).children("select").val();

        listAtt.push(lent);
    });
    var cat_id = $("#category_id").val();
    var subId = $("#sub_category_id").val();
    var data = new FormData();
    data.append("total_attribute", $("#total-attribute").html());
    data.append("_token", $("input[name=_token]").val());
    data.append("catId", cat_id);
    data.append("subId", subId);
    data.append("listAttr", listAtt);

    axios
        .post("addMoreAttribute", data)
        .then(res => {
            $("#total-attribute").html(res.data.totalAttribute);
            $("#moreAttribute > tbody").append(res.data.more);
            $("#add-more-attr").html(
                '<i class="fa fa-plus-circle" aria-hidden="true"></i>'
            );
            $(".multipleSelect").fastselect({ placeholder: 'Choose Terms' });
        })
        .catch(err => {
            console.log(err);
        });
});

$(document).on("click", ".remove-att", function (event) {
    var value = parseInt($("#total-attribute").html()) - 1;
    $("#total-attribute").html(value)
    $(this).closest("tr").remove();
});

// document.getElementById('deli-dat').addEventListener('input', function(e){
//   var day = new Date(this.value).getUTCDay();
//   if([6,0].includes(day)){
//     e.preventDefault();
//     this.value = '';
//     alert('Weekends not allowed');
//   }
// });
$(document).on("change", "#sub_category_id", function () {
    $("#total-attribute").html('0');
    $("#moreAttribute > tbody").empty();
});

$(document).on("change", "#choose_category_id, #category_id", function () {

    $("#total-attribute").html('0');
    $("#moreAttribute > tbody").empty();
    var cat_id = $(this).val();
    $("#cate_btn_third").html("");
    $("#cate_btn_forth").html("");
    $("#set_sub_category_name").val("");
    $("#sub_cate_img").html("");
    if (cat_id == "") {
        $("#set_category_name").val("");
        $("#category_position").val("");
        $("#sub_category_id").empty();
        $("#sub_category_id").append(
            '<option value="">Select Category first</option>'
        );

        $("#cate_btn_first").html(
            '<button type="button" class="btn btn-primary" id="create_category">Add Category</button>'
        );
        $("#cate_btn_secend").html("");
    } else {
        $("#set_category_name").val(
            $("#choose_category_id option:selected").text()
        );

        $("#cate_btn_first").html(
            '<button type="button" class="btn btn-primary" id="update_category">Save Category</button>'
        );
        $("#cate_btn_secend").html(
            '<button type="button" class="btn btn-primary" id="delete_category">Delete Category</button>'
        );
        $("#cate_btn_third").html(
            '<button type="button" class="btn btn-primary" id="create_sub_category">Add Sub Category</button>'
        );

        $.get("category/" + cat_id, function (data) {
            //success data
            $.each(data.categorys, function (key, val) {
                $("#choose_category_id").append(
                    '<option value="' +
                    val.id +
                    '">' +
                    val.category_name +
                    "</option>"
                );
            });
            $("#set_sub_position").append(data.subposition);
            // console.log(data.subposition);
            $("#category_position").val(data.position);
            $("#sub_category_id").empty();
            $("#sub_category_id").append(
                '<option value="">Select any Sub Category</option>'
            );
            $.each(data.subcategories, function (key, val) {
                $("#sub_category_id").append(
                    '<option value="' +
                    val.id +
                    '">' +
                    val.sub_category_name +
                    "</option>"
                );
            });
        });
    }
});
$("#cate_btn_first").on("click", "#create_category", function (e) {
    e.preventDefault();
    $("#cat_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $.ajax({
        type: "POST",
        //enctype: 'multipart/form-data',
        url: "addCategory",
        data: {
            category_name: $("#set_category_name").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            if (data.errors) {
                $("#cat_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.category_name +
                    "</div>"
                );
            } else {
                $("#choose_category_id").empty();
                $("#choose_category_id").append(
                    '<option value="">Select any Category</option>'
                );
                $.each(data.categorys, function (key, val) {
                    $("#choose_category_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.category_name +
                        "</option>"
                    );
                });
                $("#category_position").html(data.positions);
                $("#sub_category_id").empty();
                $("#sub_category_id").append(
                    '<option value="">Select Category first</option>'
                );

                $("#cat_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#set_category_name").val("");
            }
        }
    });
});
$("#cate_btn_first").on("click", "#update_category", function () {
    $("#cat_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $("#set_sub_category_name").val("");
    $("#cate_btn_third").html("");
    $("#cate_btn_forth").html("");

    $.ajax({
        type: "POST",
        //enctype: 'multipart/form-data',
        url: "saveCategory",
        //data: z,
        data: {
            id: $("#choose_category_id").val(),
            category_name: $("#set_category_name").val(),
            position: $("#category_position").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            // What to do if we error
            if (data.errors) {
                if (data.errors.category_name) {
                    $("#cat_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors.category_name +
                        "</div>"
                    );
                } else if (data.errors.position) {
                    $("#cat_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors.position +
                        "</div>"
                    );
                }
            } else {
                $("#category_position").val("");
                $("#choose_category_id").empty();
                $("#choose_category_id").append(
                    '<option value="">Select any Category</option>'
                );
                $.each(data.categorys, function (key, val) {
                    $("#choose_category_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.category_name +
                        "</option>"
                    );
                });

                $("#sub_category_id").empty();
                $("#sub_category_id").append(
                    '<option value="">Select Category first</option>'
                );
                $("#cate_btn_first").html(
                    '<button type="button" class="btn btn-primary" id="create_category">Add Category</button>'
                );
                $("#cate_btn_secend").html("");

                $("#cat_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#set_category_name").val("");
            }
        }
    });
});
$("#cate_btn_secend").on("click", "#delete_category", function () {
    var r = confirm(
        "Clicking this check box will remove all Products and Events. Are you sure you want to continue?"
    );
    if (r == true) {
        $("#cat_msg").html(
            '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
        );

        $.ajax({
            type: "POST",
            //enctype: 'multipart/form-data',
            url: "deleteCategory",
            //data: z,
            data: {
                id: $("#choose_category_id").val(),
                _token: $("input[name=_token]").val()
            },
            success: function (data) {
                // What to do if we error
                if (data.errors) {
                    $("#cat_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors +
                        "</div>"
                    );
                } else {
                    $("#choose_category_id").empty();
                    $("#choose_category_id").append(
                        '<option value="">Select any Category</option>'
                    );
                    $.each(data.categorys, function (key, val) {
                        $("#choose_category_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.category_name +
                            "</option>"
                        );
                    });
                    $("#category_position").html(data.positions);

                    $("#cate_btn_first").html(
                        '<button type="submit" id="create_category" class="btn btn-primary">Add Category</button>'
                    );

                    $("#cate_btn_secend").html("");

                    $("#sub_category_id").empty();
                    $("#sub_category_id").append(
                        '<option value="">Select Category first</option>'
                    );

                    $("#cat_msg").html(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                    $("#set_category_name").val("");
                }
            }
        });
    }
});
$("#cate_btn_third").on("click", "#create_sub_category", function () {
    $("#cat_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    var z = new FormData();
    z.append("category_id", $("#choose_category_id").val());
    z.append("sub_position", $("#set_sub_position").val());
    z.append("sub_category_name", $("#set_sub_category_name").val());
    z.append("photo", document.getElementById("set_sub_category_img").files[0]);
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "addSubCategory";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.errors) {
                $("#cat_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.sub_category_name +
                    "</div>"
                );
            } else if (data.error) {
                $("#cat_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.error +
                    "</div>"
                );
            } else {
                $("#sub_category_id").empty();
                $("#sub_category_id").append(
                    '<option value="">Select any Sub Category</option>'
                );
                $.each(data.subcategorys, function (key, val) {
                    $("#sub_category_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.sub_category_name +
                        "</option>"
                    );
                });

                $("#cat_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#set_sub_category_name").val("");
                $("#set_sub_category_img").val("");
            }
        }
    };
    ajax.send(z);
});
$("#cate_btn_third").on("click", "#update_sub_category", function () {
    $("#cat_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    var z = new FormData();
    z.append("id", $("#sub_category_id").val());
    z.append("cate_id", $("#choose_category_id").val());
    z.append("sub_category_name", $("#set_sub_category_name").val());
    z.append("sub_position", $("#set_sub_position").val());
    z.append("photo", document.getElementById("set_sub_category_img").files[0]);
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "saveSubCategory";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.errors) {
                $("#cat_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors +
                    "</div>"
                );
            } else {
                $("#sub_category_id").empty();
                $("#sub_category_id").append(
                    '<option value="">Select any Sub Category</option>'
                );
                $("#set_sub_category_name").val("");
                $("#set_sub_position").val("");
                $("#set_sub_category_img").val("");
                $.each(data.subcategorys, function (key, val) {
                    $("#sub_category_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.sub_category_name +
                        "</option>"
                    );
                });

                $("#cate_btn_third").html(
                    '<button type="button" class="btn btn-primary" id="create_sub_category">Add Sub Category</button>'
                );
                $("#cate_btn_forth").html("");

                $("#cat_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});
$("#cate_btn_forth").on("click", "#delete_sub_category", function () {
    var r = confirm(
        "Clicking this check box will remove all Products and Events. Are you sure you want to continue?"
    );
    if (r == true) {
        $("#cat_msg").html(
            '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
        );

        $.ajax({
            type: "POST",
            url: "deleteSubCategory",
            data: {
                id: $("#sub_category_id").val(),
                cate_id: $("#choose_category_id").val(),
                _token: $("input[name=_token]").val()
            },
            success: function (data) {
                // What to do if we error
                if (data.errors) {
                    $("#cat_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors +
                        "</div>"
                    );
                } else {
                    $("#sub_category_id").empty();
                    $("#sub_category_id").append(
                        '<option value="">Select any Sub Category</option>'
                    );
                    $.each(data.categorys, function (key, val) {
                        $("#sub_category_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.sub_category_name +
                            "</option>"
                        );
                    });

                    $("#cate_btn_third").html(
                        '<button type="button" class="btn btn-primary" id="create_sub_category">Add Sub Category</button>'
                    );
                    $("#cate_btn_forth").html("");
                    $("#cat_msg").html(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                    $("#set_sub_category_name").val("");
                }
            }
        });
    }
});
$("#sub_category_id").on("change", function () {
    var cat_id = $(this).val();
    $("#set_sub_category_name").val("");
    $("#cate_btn_third").html(
        '<button type="button" class="btn btn-primary" id="create_sub_category">Add Sub Category</button>'
    );
    $("#cate_btn_forth").html("");
    $("#sub_cate_img").html("");
    if (cat_id != "") {
        var z = new FormData();
        z.append("id", $("#sub_category_id").val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "editSubCategoryImage";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                var pos = data.pos;
                if (data.images != "") {
                    $("#sub_cate_img").html(data.images);
                } else {
                    $("#sub_cate_img").html("");
                }
                $("#set_sub_category_name").val(
                    $("#sub_category_id option:selected").text()
                );
                $("#set_sub_position")
                    .val(pos)
                    .change();
                $("#cate_btn_third").html(
                    '<button type="button" class="btn btn-primary" id="update_sub_category">Save Sub Category</button>'
                );
                $("#cate_btn_forth").html(
                    '<button type="button" class="btn btn-primary" id="delete_sub_category">Delete Sub Category</button>'
                );
            }
        };
        ajax.send(z);
    }
});

$("#choose_attribute_id").on("change", function () {
    var attribute_id = $(this).val();
    $("#attr_btn_third").html("");
    $("#attr_btn_forth").html("");
    if (attribute_id == "") {
        $("#set_attribute_name").val("");
        $("#term_id").empty();
        $("#term_id").append(
            '<option value="">Select Attribute first</option>'
        );

        $("#attr_btn_first").html(
            '<button type="button" class="btn btn-primary" id="create_attribute">Add Attribute</button>'
        );
        $("#attr_btn_secend").html("");
    } else {
        $("#set_attribute_name").val(
            $("#choose_attribute_id option:selected").text()
        );

        $("#attr_btn_first").html(
            '<button type="button" class="btn btn-primary" id="update_attribute">Save Attribute</button>'
        );
        $("#attr_btn_secend").html(
            '<button type="button" class="btn btn-primary" id="delete_attribute">Delete Attribute</button>'
        );
        $("#attr_btn_third").html(
            '<button type="button" class="btn btn-primary" id="create_term">Add Sub Attribute</button>'
        );

        $.get("attribute/" + attribute_id, function (data) {
            //success data
            $("#term_id").empty();
            $("#term_id").append('<option value="">Select any Term</option>');
            $.each(data, function (index, subcatObj) {
                $("#term_id").append(
                    '<option value="' +
                    subcatObj.id +
                    '">' +
                    subcatObj.name +
                    "</option>"
                );
            });
        });
    }
});
$("#attr_btn_first").on("click", "#create_attribute", function () {
    $("#attr_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    var cat_id = $("#att_category_id").val();
    var subId = $("#att_sub_category_id").val();
    $.ajax({
        type: "POST",
        //enctype: 'multipart/form-data',
        url: "addAttribute",
        data: {
            name: $("#set_attribute_name").val(),
            _token: $("input[name=_token]").val(),
            category_id: cat_id,
            subcategory_id: subId
        },
        success: function (data) {
            if (data.errors) {
                $("#attr_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors +
                    "</div>"
                );
            } else {
                window.location = "attribute";
            }
        }
    });
});
$("#attr_btn_first").on("click", "#update_attribute", function () {
    // alert($("#choose_attribute_id").val())
    $("#attr_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $("#set_term_name").val("");
    $("#attr_btn_third").html("");
    $("#attr_btn_forth").html("");
    $.ajax({
        type: "POST",
        //enctype: 'multipart/form-data',
        url: "saveAttribute",
        //data: z,
        data: {
            id: $("#choose_attribute_id").val(),
            name: $("#set_attribute_name").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            // What to do if we error
            if (data.errors) {
                $("#attr_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors +
                    "</div>"
                );
            } else {
                window.location = "attribute";
            }
        }
    });
});
$("#attr_btn_secend").on("click", "#delete_attribute", function () {
    var r = confirm(
        "Clicking this check box will remove all Products and Events. Are you sure you want to continue?"
    );
    if (r == true) {
        $("#attr_msg").html(
            '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
        );

        $.ajax({
            type: "POST",
            //enctype: 'multipart/form-data',
            url: "deleteAttribute",
            //data: z,
            data: {
                id: $("#choose_attribute_id").val(),
                _token: $("input[name=_token]").val()
            },
            success: function (data) {
                // What to do if we error
                if (data.errors) {
                    $("#attr_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors +
                        "</div>"
                    );
                } else {
                    $("#choose_attribute_id").empty();
                    $("#choose_attribute_id").append(
                        '<option value="">Select Any Attribut</option>'
                    );
                    $.each(data.attributes, function (key, val) {
                        $("#choose_attribute_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.name +
                            "</option>"
                        );
                    });

                    $("#term_id").empty();
                    $("#term_id").append(
                        '<option value="">Select Attribute first</option>'
                    );

                    $("#attr_msg").html(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                    $("#set_attribute_name").val("");
                }
            }
        });
    }
});
$("#attr_btn_third").on("click", "#create_term", function () {
    $("#attr_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $.ajax({
        type: "POST",
        url: "addTermAttribute",
        data: {
            attribute_id: $("#choose_attribute_id").val(),
            name: $("#set_term_name").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            if (data.errors) {
                $("#attr_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.name +
                    data.error +
                    "</div>"
                );
            } else {
                $("#term_id").empty();
                $("#term_id").append(
                    '<option value="">Select any Term</option>'
                );
                $.each(data.terms, function (key, val) {
                    $("#term_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });

                $("#attr_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#set_term_name").val("");
            }
        }
    });
});
$("#attr_btn_third").on("click", "#update_term", function () {
    $("#attr_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $.ajax({
        type: "POST",
        //enctype: 'multipart/form-data',
        url: "saveTermAttribute",
        //data: z,
        data: {
            id: $("#term_id").val(),
            attribute_id: $("#choose_attribute_id").val(),
            name: $("#set_term_name").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            // What to do if we error
            if (data.errors) {
                $("#attr_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors +
                    "</div>"
                );
            } else {
                $("#term_id").empty();
                $("#term_id").append(
                    '<option value="">Select any Term</option>'
                );
                $.each(data.terms, function (key, val) {
                    $("#term_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });

                $("#attr_btn_third").html(
                    '<button type="button" class="btn btn-primary" id="create_term">Add Term</button>'
                );
                $("#attr_btn_forth").html("");

                $("#attr_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#set_term_name").val("");
            }
        }
    });
});
$("#attr_btn_forth").on("click", "#delete_term", function () {
    var r = confirm(
        "Clicking this check box will remove all Terms and Their setings from your Database. Are you sure you want to continue?"
    );
    if (r == true) {
        $("#attr_msg").html(
            '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
        );

        $.ajax({
            type: "POST",
            url: "deleteTermAttribute",
            data: {
                id: $("#term_id").val(),
                attribute_id: $("#choose_attribute_id").val(),
                _token: $("input[name=_token]").val()
            },
            success: function (data) {
                // What to do if we error
                if (data.errors) {
                    $("#attr_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors +
                        "</div>"
                    );
                } else {
                    $("#term_id").empty();
                    $("#term_id").append(
                        '<option value="">Select any Term</option>'
                    );
                    $.each(data.terms, function (key, val) {
                        $("#term_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.name +
                            "</option>"
                        );
                    });

                    $("#attr_btn_third").html(
                        '<button type="button" class="btn btn-primary" id="create_term">Add Term</button>'
                    );
                    $("#attr_btn_forth").html("");
                    $("#attr_msg").html(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                    $("#set_term_name").val("");
                }
            }
        });
    }
});
$("#term_id").on("change", function () {
    var cat_id = $(this).val();
    if (cat_id == "") {
        $("#set_term_name").val("");

        $("#attr_btn_third").html(
            '<button type="button" class="btn btn-primary" id="create_term">Add Term</button>'
        );
        $("#attr_btn_forth").html("");
    } else {
        $("#set_term_name").val($("#term_id option:selected").text());
        $("#attr_btn_third").html(
            '<button type="button" class="btn btn-primary" id="update_term">Save Term</button>'
        );
        $("#attr_btn_forth").html(
            '<button type="button" class="btn btn-primary" id="delete_term">Delete Term</button>'
        );
    }
});






$("#add_booking").on("click", function (e) {
    e.preventDefault();
    $("#booking_msg").html(
        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>Please Wait...</div>'
    );

    var z = new FormData();
    z.append("category_id", $("#category_id").val());
    z.append("sub_category_id", $("#sub_category_id").val());
    z.append("name", $("#name").val());
    z.append("location", $("#location").val());
    z.append("language", $("#language").val());
    z.append("enlisted_in", $("#enlisted_in").val());
    z.append("preferable_events", $("#preferable_events").val());
    z.append("preferable_place", $("#preferable_place").val());
    z.append("performane_duration", $("#performane_duration").val());
    z.append("price", $("#price").val());
    z.append("performance_fee", $("#performance_fee").val());
    for (i = 0; i < document.getElementById("photo").files.length; i++) {
        z.append("photo[]", document.getElementById("photo").files[i]);
    }
    z.append("video", $("#video").val());
    z.append("on_stage_team", $("#on_stage_team").val());
    z.append("off_stage_team", $("#off_stage_team").val());
    z.append("off_stage_food", $("#off_stage_food").val());
    z.append("details", $("#details").val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "addBooking";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);

            if (data.msg) {
                $("form")
                    .find(
                        "input[type=text], input[type=file], select, textarea"
                    )
                    .val("");
                $("#booking_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#all_booking").html(data.bookings);
                setTimeout(function () {
                    $("#bookingAdd").modal("hide");
                }, 3000);
            } else if (data.errors.category_id) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.category_id +
                    "</div>"
                );
            } else if (data.errors.name) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.name +
                    "</div>"
                );
            } else if (data.errors.location) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.location +
                    "</div>"
                );
            } else if (data.errors.language) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.language +
                    "</div>"
                );
            } else if (data.errors.enlisted_in) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.enlisted_in +
                    "</div>"
                );
            } else if (data.errors.preferable_events) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.preferable_events +
                    "</div>"
                );
            } else if (data.errors.preferable_place) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.preferable_place +
                    "</div>"
                );
            } else if (data.errors.performane_duration) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.performane_duration +
                    "</div>"
                );
            } else if (data.errors.price) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.price +
                    "</div>"
                );
            } else if (data.errors.performance_fee) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.performance_fee +
                    "</div>"
                );
            } else if (data.errors.photo) {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.photo +
                    "</div>"
                );
            } else {
                $("#booking_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    JSON.stringify(data.errors) +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});

$("#bookingEdit").on("click", "#save_booking", function (e) {
    e.preventDefault();
    $("#booking_edit_msg").html(
        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>Please Wait...</div>'
    );

    var z = new FormData();
    z.append("id", $("#booking_id").val());
    z.append("category_id", $("#category").val());
    z.append("sub_category_id", $("#sub_category").val());
    z.append("name", $("#book_name").val());
    z.append("location", $("#book_location").val());
    z.append("language", $("#book_language").val());
    z.append("enlisted_in", $("#enlisted").val());
    z.append("preferable_events", $("#events").val());
    z.append("preferable_place", $("#place").val());
    z.append("performane_duration", $("#duration").val());
    z.append("price", $("#book_price").val());
    z.append("performance_fee", $("#fee").val());
    for (i = 0; i < document.getElementById("image").files.length; i++) {
        z.append("photo[]", document.getElementById("image").files[i]);
    }
    z.append("video", $("#book_video").val());
    z.append("on_stage_team", $("#on_stag_team").val());
    z.append("off_stage_team", $("#off_stag_team").val());
    z.append("off_stage_food", $("#off_stag_food").val());
    z.append("details", $("#book_details").val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "saveBooking";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);

            if (data.msg) {
                $("form")
                    .find(
                        "input[type=text], input[type=file], select, textarea"
                    )
                    .val("");
                $("#booking_edit_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#all_booking").html(data.bookings);
                setTimeout(function () {
                    $("#bookingEdit").modal("hide");
                    $("#booking_edit_msg").html("");
                }, 3000);
            } else if (data.errors.category_id) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.category_id +
                    "</div>"
                );
            } else if (data.errors.name) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.name +
                    "</div>"
                );
            } else if (data.errors.location) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.location +
                    "</div>"
                );
            } else if (data.errors.language) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.language +
                    "</div>"
                );
            } else if (data.errors.enlisted_in) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.enlisted_in +
                    "</div>"
                );
            } else if (data.errors.preferable_events) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.preferable_events +
                    "</div>"
                );
            } else if (data.errors.preferable_place) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.preferable_place +
                    "</div>"
                );
            } else if (data.errors.performane_duration) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.performane_duration +
                    "</div>"
                );
            } else if (data.errors.price) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.price +
                    "</div>"
                );
            } else if (data.errors.performance_fee) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.performance_fee +
                    "</div>"
                );
            } else if (data.errors.photo) {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.photo +
                    "</div>"
                );
            } else {
                $("#booking_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    JSON.stringify(data.errors) +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});
$("#all_booking").on("click", ".delete_booking", function (e) {
    var z = new FormData();
    z.append("id", $(this).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "deleteBooking";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.bookings != "") {
                alert(data.msg);
                $("#all_booking").html(data.bookings);
            }
        }
    };
    ajax.send(z);
});

$("#lavle_btn_first").on("click", "#create_lavle", function () {
    $("#level_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    $.ajax({
        type: "POST",
        //enctype: 'multipart/form-data',
        url: "addLevel",
        data: {
            name: $("#set_level_name").val(),
            commission: $("#set_level_commission").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            if (data.errors) {
                //var data = JSON.parse(ajax.responseText);
                //alert(JSON.stringify(data.errors));
                //alert(data.errors.name);
                if (typeof data.errors.name != "undefined") {
                    $("#level_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors.name +
                        "</div>"
                    );
                } else {
                    $("#level_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors.commission +
                        "</div>"
                    );
                }
            } else {
                $("input").val("");
                $("#level_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#choose_level_id").empty();
                $("#choose_level_id").append(
                    '<option value="">Select any Level</option>'
                );
                $.each(data.levels, function (key, val) {
                    $("#choose_level_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });
            }
        }
    });
});
$("#choose_level_id").on("change", function () {
    var level_id = $("#choose_level_id").val();
    $("#set_level_name").html("");
    $("#set_level_commission").val("");
    $("#lavle_btn_first").html(
        '<button type="button" class="btn btn-primary" id="create_lavle">Add Lavle</button>'
    );
    //alert($("meta[name=csrf-token]").attr("content"));
    if (level_id != "") {
        var z = new FormData();
        z.append("id", $("#choose_level_id").val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "editLevel";
        ajax.open("POST", url, true);
        ajax.setRequestHeader(
            "X-CSRF-TOKEN",
            $('meta[name="csrf-token"]').attr("content")
        );
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                $("#set_level_commission").val(data.commission);
                $("#set_level_name").val(
                    $("#choose_level_id option:selected").text()
                );

                $("#lavle_btn_first").html(
                    '<button type="button" class="btn btn-primary" id="save_lavle">Save Lavle</button> <button type="button" class="btn btn-danger" id="delete_lavle">Delete Lavle</button>'
                );
            }
        };
        ajax.send(z);
    }
});
$("#lavle_btn_first").on("click", "#save_lavle", function () {
    $("#level_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    $.ajax({
        type: "POST",
        //enctype: 'multipart/form-data',
        url: "saveLevel",
        data: {
            id: $("#choose_level_id").val(),
            name: $("#set_level_name").val(),
            commission: $("#set_level_commission").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            // What to do if we error
            //alert(JSON.stringify(data.errors));
            //alert(data.errors.name);
            if (typeof data.errors != "undefined") {
                $("#level_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors +
                    "</div>"
                );
            } else {
                $("input").val("");
                $("#level_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#choose_level_id").empty();
                $("#choose_level_id").append(
                    '<option value="">Select any Level</option>'
                );
                $.each(data.levels, function (key, val) {
                    $("#choose_level_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });
            }
        }
    });
});
$("#lavle_btn_first").on("click", "#delete_lavle", function () {
    var r = confirm(
        "Clicking this check box will remove all Products and Events. Are you sure you want to continue?"
    );
    if (r == true) {
        var level_id = $("#choose_level_id").val();
        if (level_id != "") {
            var z = new FormData();
            z.append("id", $("#choose_level_id").val());
            z.append("_token", $("input[name=_token]").val());
            var ajax = new XMLHttpRequest();
            var url = "deleteLevel";
            ajax.open("POST", url, true);
            ajax.setRequestHeader(
                "X-CSRF-TOKEN",
                $('meta[name="csrf-token"]').attr("content")
            );
            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    var data = JSON.parse(ajax.responseText);
                    $("#set_level_commission").val("");
                    $("#set_level_name").val("");

                    $("#lavle_btn_first").html(
                        '<button type="button" class="btn btn-primary" id="create_lavle">Add Lavle</button>'
                    );

                    $("#choose_level_id").empty();
                    $("#choose_level_id").append(
                        '<option value="">Select any Level</option>'
                    );
                    $.each(data.levels, function (key, val) {
                        $("#choose_level_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.name +
                            "</option>"
                        );
                    });
                    $("#level_msg").html(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                }
            };
            ajax.send(z);
        } else {
            $("#level_msg").html(
                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> Choose Any Level first</div>'
            );
        }
    }
});

$("#seller_btn_first").on("click", "#create_seller", function () {
    $("#seler_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    var z = new FormData();
    z.append("name", $("#set_seller_name").val());
    z.append("level_id", $("#set_seller_level").val());
    z.append("address", $("#set_seller_address").val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "addSeller";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.errors) {
                //var data = JSON.parse(ajax.responseText);
                //alert(JSON.stringify(data.errors));
                //alert(data.errors.name);
                if (typeof data.errors.name != "undefined") {
                    $("#seler_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors.name +
                        "</div>"
                    );
                } else if (typeof data.errors.level_id != "undefined") {
                    $("#seler_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors.level_id +
                        "</div>"
                    );
                } else {
                    $("#seler_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors.address +
                        "</div>"
                    );
                }
            } else {
                $("input").val("");
                $("select").val("");
                $("textarea").val("");
                $("#seler_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#choose_seller_id").empty();
                $("#choose_seller_id").append(
                    '<option value="">Select any Level</option>'
                );
                $.each(data.sellers, function (key, val) {
                    $("#choose_seller_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });
            }
        }
    };
    ajax.send(z);
});
$("#choose_seller_id").on("change", function () {
    var seller_id = $("#choose_seller_id").val();
    $("#set_seller_name").val("");
    $("#set_seller_level").val("");
    $("#set_seller_address").val("");
    $("#seller_btn_first").html(
        '<button type="button" class="btn btn-primary" id="create_seller">Add Seller</button>'
    );

    if (seller_id != "") {
        var z = new FormData();
        z.append("id", $("#choose_seller_id").val());
        z.append("name", $("#set_seller_name").val());
        z.append("level_id", $("#set_seller_level").val());
        z.append("address", $("#set_seller_address").val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "editSeller";
        ajax.open("POST", url, true);
        ajax.setRequestHeader(
            "X-CSRF-TOKEN",
            $('meta[name="csrf-token"]').attr("content")
        );
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                $("#set_seller_name").val(
                    $("#choose_seller_id option:selected").text()
                );
                $("#set_seller_level").val(data.level);
                $("#set_seller_address").val(data.address);

                $("#seller_btn_first").html(
                    '<button type="button" class="btn btn-primary" id="save_seller">Save Seller</button> <button type="button" class="btn btn-danger" id="delete_seller">Delete Seller</button>'
                );
            }
        };
        ajax.send(z);
    }
});
$("#seller_btn_first").on("click", "#save_seller", function () {
    $("#level_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    $.ajax({
        type: "POST",
        //enctype: 'multipart/form-data',
        url: "saveSeller",
        data: {
            id: $("#choose_seller_id").val(),
            name: $("#set_seller_name").val(),
            level_id: $("#set_seller_level").val(),
            address: $("#set_seller_address").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            // What to do if we error
            //alert(JSON.stringify(data.errors));
            //alert(data.errors.name);
            if (typeof data.errors != "undefined") {
                $("#seler_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors +
                    "</div>"
                );
            } else {
                $("#set_seller_name").val("");
                $("#set_seller_level").val("");
                $("#set_seller_address").val("");
                $("#seller_btn_first").html(
                    '<button type="button" class="btn btn-primary" id="create_seller">Add Seller</button>'
                );

                $("#seler_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#choose_seller_id").empty();
                $("#choose_seller_id").append(
                    '<option value="">Select any Seller</option>'
                );
                $.each(data.sellers, function (key, val) {
                    $("#choose_seller_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });
            }
        }
    });
});
$("#seller_btn_first").on("click", "#delete_seller", function () {
    var r = confirm(
        "Clicking this check box will remove all Products and Events. Are you sure you want to continue?"
    );
    if (r == true) {
        var seller_id = $("#choose_seller_id").val();
        if (seller_id != "") {
            $("#set_seller_name").val("");
            $("#set_seller_level").val("");
            $("#set_seller_address").val("");
            $("#seler_msg").html(
                '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
            );
            var z = new FormData();
            z.append("id", $("#choose_seller_id").val());
            z.append("_token", $("input[name=_token]").val());
            var ajax = new XMLHttpRequest();
            var url = "deleteSeller";
            ajax.open("POST", url, true);
            ajax.setRequestHeader(
                "X-CSRF-TOKEN",
                $('meta[name="csrf-token"]').attr("content")
            );
            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    var data = JSON.parse(ajax.responseText);

                    $("#seller_btn_first").html(
                        '<button type="button" class="btn btn-primary" id="create_seller">Add Seller</button>'
                    );

                    $("#choose_seller_id").empty();
                    $("#choose_seller_id").append(
                        '<option value="">Select any Seller</option>'
                    );
                    $.each(data.sellers, function (key, val) {
                        $("#choose_seller_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.name +
                            "</option>"
                        );
                    });
                    $("#seler_msg").html(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                }
            };
            ajax.send(z);
        } else {
            $("#seler_msg").html(
                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> Choose Any Seller first</div>'
            );
        }
    }
});
$("#footer-category, #sub-category").on("change", function () {
    if ($(this).val() != "") {
        window.location.href = $(this).val();
    }
});

$("#uploadformbanner").on("submit", function (event) {
    event.preventDefault();
    $("#msg_banner").html(
        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>Wait Please...</div>'
    );
    $.ajax({
        url: "addBanner",
        method: "POST",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            // var data = JSON.parse(ajax.responseText);
            if (data.msg) {
                $("#msg_banner").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("form")
                    .find("input[type=file]")
                    .val("");
                setTimeout(function () {
                    $("#bannerAdd").modal("hide");
                    $("#msg_banner").html("");
                }, 3000);
                $("#all_banners").html(data.banners);
            } else if (data.errors.photo) {
                $("#msg_banner").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.photo +
                    "</div>"
                );
            } else {
                Object.values(data.errors).map((item, i) => {
                    $("#msg_banner").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        item[0] +
                        "</div>"
                    );
                    // console.log(i, item[0]);
                });
            }
        }
    });
});
$("#all_banners").on("click", ".delet_banner", function (e) {
    var r = confirm("Are you sure you want to delete?");
    if (r == true) {
        var z = new FormData();
        z.append("id", $(this).val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "deleteBanner";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                if (data.msg) {
                    alert(data.msg);
                    setTimeout(function () {
                        //$('#bannerAdd').modal('hide');
                    }, 3000);
                    $("#all_banners").html(data.banners);
                } else {
                    alert(data.errors);
                }
            }
        };
        ajax.send(z);
    }
});

$("#state_btn_first").on("click", "#create_state", function () {
    $("#state_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );

    $.ajax({
        type: "POST",
        url: "addState",
        data: {
            name: $("#set_state_name").val(),
            phone: $("#set_state_phone").val(),
            address: $("#set_state_address").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            if (data.errors) {
                $("#state_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.name +
                    "</div>"
                );
            } else {
                $("#choose_state_id").empty();
                $("#choose_state_id").append(
                    '<option value="">Select any State</option>'
                );
                $.each(data.states, function (key, val) {
                    $("#choose_state_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });

                $("#state_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#set_state_name").val("");
                $("#set_state_phone").val("");
                $("#set_state_address").val("");
            }
        }
    });
});
$("#choose_state_id").on("change", function () {
    var state_id = $(this).val();
    $("#state_msg").html("");
    if (state_id == "") {
        $("#set_state_name").val("");
        $("#set_state_phone").val("");
        $("#set_state_address").val("");
        $("#state_btn_first").html(
            '<button type="button" class="btn btn-primary" id="create_state">Add State</button>'
        );
        $("#state_btn_secend").html("");
    } else {
        $("#state_msg").html(
            '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
        );
        var z = new FormData();
        z.append("state_id", state_id);
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "showState";
        ajax.open("POST", url, true);
        ajax.setRequestHeader(
            "X-CSRF-TOKEN",
            $('meta[name="csrf-token"]').attr("content")
        );
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                //alert(JSON.stringify(data.errors));
                var data = JSON.parse(ajax.responseText);
                $("#set_state_name").val(data.name);
                $("#set_state_phone").val(data.phone);
                $("#set_state_address").val(data.address);
                $("#state_msg").html("");
                $("#state_btn_first").html(
                    '<button type="button" class="btn btn-primary" id="update_state">Save State</button>'
                );
                $("#state_btn_secend").html(
                    '<button type="button" class="btn btn-primary" id="delete_state">Delete State</button>'
                );
            }
        };
        ajax.send(z);
    }
});
$("#state_btn_first").on("click", "#update_state", function () {
    $("#state_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    $.ajax({
        type: "POST",
        url: "saveState",
        //data: z,
        data: {
            id: $("#choose_state_id").val(),
            name: $("#set_state_name").val(),
            phone: $("#set_state_phone").val(),
            address: $("#set_state_address").val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            // What to do if we error
            if (data.errors) {
                $("#state_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors +
                    "</div>"
                );
            } else {
                $("#choose_state_id").empty();
                $("#choose_state_id").append(
                    '<option value="">Select any state</option>'
                );
                $.each(data.states, function (key, val) {
                    $("#choose_state_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });

                $("#state_btn_first").html(
                    '<button type="button" class="btn btn-primary" id="create_state">Add State</button>'
                );
                $("#state_btn_secend").html("");

                $("#state_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("#set_state_name").val("");
                $("#set_state_phone").val("");
                $("#set_state_address").val("");
            }
        }
    });
});
$("#state_btn_secend").on("click", "#delete_state", function () {
    var r = confirm(
        "Clicking this check box will remove all Products and Events. Are you sure you want to continue?"
    );
    if (r == true) {
        $("#state_msg").html(
            '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
        );

        $.ajax({
            type: "POST",
            //enctype: 'multipart/form-data',
            url: "deleteState",
            //data: z,
            data: {
                id: $("#choose_state_id").val(),
                _token: $("input[name=_token]").val()
            },
            success: function (data) {
                // What to do if we error
                if (data.errors) {
                    $("#state_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors +
                        "</div>"
                    );
                } else {
                    $("#choose_state_id").empty();
                    $("#choose_state_id").append(
                        '<option value="">Select any state</option>'
                    );
                    $.each(data.states, function (key, val) {
                        $("#choose_state_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.name +
                            "</option>"
                        );
                    });

                    $("#state_msg").html(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                    $("#set_state_name").val("");
                }
            }
        });
    }
});

if ($(".multipleSelect").length != 0) {
    $(".multipleSelect").fastselect();
}

window.textCounter = function (a, b, mxlen) {
    var len = document.getElementById(a).value.length;
    document.getElementById(b).innerHTML = mxlen - len;
};

$("#submitReview").on("click", function (e) {
    e.preventDefault();
    $("#review_alert").html(
        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>Please Wait...</div>'
    );
    var str = document.getElementsByName("rating");
    var strval = "";
    for (var i = 0; i < str.length; i++) {
        if (str[i].checked) {
            strval = str[i].value;
            break;
        }
    }
    //alert(strval);
    var z = new FormData();
    z.append("review_for", $("#item_id").val());
    z.append("name", $("#my_nam").val());
    z.append("email", $("#my_em").val());
    z.append("phone", $("#my_ph").val());
    z.append("rating", strval);
    z.append("review", $("#my_comm").val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "review";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);

            if (data.msg) {
                $("form")
                    .find("input[id!=item_id], textarea")
                    .val("");
                $("#review_alert").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $(".stars").html(data.reviews);
                $(".review-no").html(
                    '<button type="button" class="btn btn-outline-info btn-sm"  data-toggle="modal" data-target="#reviewshow">' +
                    data.totalReviews +
                    "</button>"
                );
                $("#all_review").html(data.allReviews);
                setTimeout(function () {
                    $("#reviewModal").modal("hide");
                    $("#review_alert").html("");
                }, 3000);
            }
            if (data.errors.name) {
                $("#review_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.name +
                    "</div>"
                );
            } else if (data.errors.email) {
                $("#review_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.email +
                    "</div>"
                );
            } else if (data.errors.phone) {
                $("#review_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.phone +
                    "</div>"
                );
            } else if (data.errors.rating) {
                $("#review_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.rating +
                    "</div>"
                );
            } else if (data.errors.review) {
                $("#review_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.review +
                    "</div>"
                );
            } else {
                $("#review_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    JSON.stringify(data.errors) +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});

$("#dep_btn_first").on("click", "#create_dep", function (e) {
    e.preventDefault();
    $("#item_id").empty();
    $("#item_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    var data = new FormData();
    data.append("name", $("#dp-name").val());
    data.append("_token", $("input[name=_token]").val());
    axios.post("department", data)
        .then(res => {
            if (res.data.msg) {
                $("input").val("");
                $("select").val("");
                $("#item_msg").html('');
                $("#department_id").empty();
                $("#department_id").append(
                    '<option value="">Choose...</option>'
                );
                $.each(res.data.departments, function (key, val) {
                    $("#department_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: res.data.msg,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                var errorString = '<ul>';
                $.each(res.data.errors, function (key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                $("#item_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    errorString + '</div>'
                );
            }
        })
        .catch(err => {
            var errorString = '<ul>';
            $.each(err.response.data.errors, function (key, value) {
                errorString += '<li>' + value + '</li>';
            });
            errorString += '</ul>';
            $("#item_msg").html(
                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                errorString + '</div>'
            );
        });
});
$("#department_id").on("change", function () {
    var item_id = $(this).val();
    $("input").val("");
    $("#item_id").empty();
    $("#sw_unit").val("");
    $("#q_unit").val("");
    $("#dep_btn_secend").html("");
    $("#itm_btn_first").html('');
    $("#itm_btn_secend").html("");
    $("#item_msg").html('');

    if (item_id == "") {
        $("#dep_btn_first").html(
            '<button type="button" class="btn btn-primary" id="create_dep">Add Department</button>'
        );
    } else {
        axios
            .get("department/" + item_id + "/edit")
            .then(res => {
                $("#dp-name").val(res.data.name);
                $("#dep_btn_first").html(res.data.btn1);
                $("#dep_btn_secend").html(res.data.btn2);

                $("#item_id").append(
                    '<option value="">Choose...</option>'
                );
                $.each(res.data.items, function (key, val) {
                    $("#item_id").append(
                        '<option value="' +
                        val.id +
                        '">' +
                        val.name +
                        "</option>"
                    );
                });
                $("#itm_btn_first").html(res.data.btn3);
            })
            .catch(err => {
                var errorString = '<ul>';
                $.each(err.response.data.errors, function (key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                $("#item_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    errorString + '</div>'
                );
            });
    }
});
$("#dep_btn_first").on("click", "#save_dep", function (e) {
    e.preventDefault();
    $("#item_id").empty();
    $("#sw_unit").val("");
    $("#q_unit").val("");
    $("#itm_btn_first").html('');
    $("#itm_btn_secend").html("");
    $("#item_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    var data = new FormData();
    data.append("id", $("#department_id").val());
    data.append("name", $("#dp-name").val());
    data.append("_token", $("input[name=_token]").val());
    data.append("_method", "PUT");

    axios
        .post("department/" + data.get("id"), data)
        .then(res => {
            $("#item_msg").html('');
            $("input").val("");
            $("select").val("");
            $("#item_msg").html('');
            $("#department_id").empty();
            $("#department_id").append(
                '<option value="">Choose...</option>'
            );
            $.each(res.data.departments, function (key, val) {
                $("#department_id").append(
                    '<option value="' +
                    val.id +
                    '">' +
                    val.name +
                    "</option>"
                );
            });
            $("#dep_btn_first").html(res.data.btn1);
            $("#dep_btn_secend").html("");
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: res.data.msg,
                showConfirmButton: false,
                timer: 1500
            });
        })
        .catch(err => {
            var errorString = '<ul>';
            $.each(err.response.data.errors, function (key, value) {
                errorString += '<li>' + value + '</li>';
            });
            errorString += '</ul>';
            $("#item_msg").html(
                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                errorString + '</div>'
            );
        });
});
$("#dep_btn_secend").on("click", "#delete_dep", function (e) {
    e.preventDefault();
    var data = new FormData();
    data.append("id", $("#department_id").val());
    data.append("_token", $("input[name=_token]").val());
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(result => {
        if (result.value) {
            axios
                .delete("department/" + data.get("id"))
                .then(res => {
                    $("#name").val("");
                    $("#sw").val("");
                    $("#sw_unit").val("");
                    $("#qty").val("");
                    $("#q_unit").val("");
                    $("#price").val("");
                    $("#item_id").empty();
                    $("#department_id").empty();
                    $("#department_id").append(
                        '<option value="">Choose...</option>'
                    );
                    $.each(res.data.departments, function (key, val) {
                        $("#department_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.name +
                            "</option>"
                        );
                    });
                    $("#dep_btn_first").html(res.data.btn1);
                    $("#dep_btn_secend").html("");
                })
                .catch(err => {
                    var errorString = '<ul>';
                    $.each(err.response.data.errors, function (key, value) {
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';
                    $("#item_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        errorString + '</div>'
                    );
                });
        }
    });
});

$("#itm_btn_first").on("click", "#create_item", function (e) {
    e.preventDefault();
    $("#item_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    var data = new FormData();
    data.append("department", $("#department_id").val());
    data.append("name", $("#name").val());
    data.append("size_weight", $("#sw").val());
    data.append("sw_unit", $("#sw_unit").val());
    data.append("quantity", $("#qty").val());
    data.append("q_unit", $("#q_unit").val());
    data.append("price", $("#price").val());
    data.append("_token", $("input[name=_token]").val());
    axios.post("items", data)
        .then(res => {
            $("#name").val("");
            $("#sw").val('');
            $("#sw_unit").val('');
            $("#qty").val('');
            $("#q_unit").val('');
            $("#price").val('');
            $("#item_msg").html('');
            $("#item_id").empty();
            $("#item_id").append(
                '<option value="">Choose...</option>'
            );
            $.each(res.data.items, function (key, val) {
                $("#item_id").append(
                    '<option value="' +
                    val.id +
                    '">' +
                    val.name +
                    "</option>"
                );
            });
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: res.data.msg,
                showConfirmButton: false,
                timer: 1500
            });
        })
        .catch(err => {
            var errorString = '<ul>';
            $.each(err.response.data.errors, function (key, value) {
                errorString += '<li>' + value + '</li>';
            });
            errorString += '</ul>';
            $("#item_msg").html(
                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                errorString + '</div>'
            );
        });
});
$("#item_id").on("change", function () {
    var item_id = $(this).val();
    if (item_id == "") {
        $("#name").val("");
        $("#sw").val("");
        $("#sw_unit").val("");
        $("#qty").val("");
        $("#q_unit").val("");
        $("#price").val("");
        $("#itm_btn_first").html(
            '<button type="button" class="btn btn-primary" id="create_item">Add Item</button>'
        );
        $("#itm_btn_secend").html("");
    } else {

        axios
            .get("items/" + item_id + "/edit")
            .then(res => {
                $("#name").val(res.data.itmName);
                $("#sw").val(res.data.itmSW);
                $("#sw_unit").val(res.data.itmSWunit);
                $("#qty").val(res.data.itmQty);
                $("#q_unit").val(res.data.itmQunit);
                $("#price").val(res.data.itmPrice);
                $("#itm_btn_first").html(res.data.btn1);
                $("#itm_btn_secend").html(res.data.btn2);
            })
            .catch(err => {
                var errorString = '<ul>';
                $.each(err.response.data.errors, function (key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                $("#item_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    errorString + '</div>'
                );
            });
    }
});
$("#itm_btn_first").on("click", "#save_item", function (e) {
    e.preventDefault();
    $("#item_msg").html(
        '<div class="alert alert-danger alert-dismissable">Please Wait...</div>'
    );
    var data = new FormData();
    data.append("department", $("#department_id").val());
    data.append("id", $("#item_id").val());
    data.append("name", $("#name").val());
    data.append("size_weight", $("#sw").val());
    data.append("sw_unit", $("#sw_unit").val());
    data.append("quantity", $("#qty").val());
    data.append("q_unit", $("#q_unit").val());
    data.append("price", $("#price").val());
    data.append("_token", $("input[name=_token]").val());
    data.append("_method", "PUT");
    axios
        .post("items/" + data.get("id"), data)
        .then(res => {
            $("#name").val("");
            $("#sw").val('');
            $("#sw_unit").val('');
            $("#qty").val('');
            $("#q_unit").val('');
            $("#price").val('');
            $("#item_msg").html('');
            $("#item_id").empty();
            $("#item_id").append(
                '<option value="">Choose...</option>'
            );
            $.each(res.data.items, function (key, val) {
                $("#item_id").append(
                    '<option value="' +
                    val.id +
                    '">' +
                    val.name +
                    "</option>"
                );
            });
            $("#itm_btn_first").html(res.data.btn1);
            $("#itm_btn_secend").html('');
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: res.data.msg,
                showConfirmButton: false,
                timer: 1500
            });
        })
        .catch(err => {
            var errorString = '<ul>';
            $.each(err.response.data.errors, function (key, value) {
                errorString += '<li>' + value + '</li>';
            });
            errorString += '</ul>';
            $("#item_msg").html(
                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                errorString + '</div>'
            );
        });
});
$("#itm_btn_secend").on("click", "#delete_item", function (e) {
    e.preventDefault();
    var data = new FormData();
    data.append("id", $("#department_id").val() + '-' + $("#item_id").val());
    data.append("_token", $("input[name=_token]").val());
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(result => {
        if (result.value) {
            axios
                .delete("items/" + data.get("id"))
                .then(res => {
                    $("#name").val("");
                    $("#sw").val("");
                    $("#sw_unit").val("");
                    $("#qty").val("");
                    $("#q_unit").val("");
                    $("#price").val("");
                    $("#item_id").empty();
                    $("#item_id").append(
                        '<option value="">Choose...</option>'
                    );
                    $.each(res.data.items, function (key, val) {
                        $("#item_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.name +
                            "</option>"
                        );
                    });
                    $("#itm_btn_first").html(res.data.btn1);
                    $("#itm_btn_secend").html('');
                })
                .catch(err => {
                    var errorString = '<ul>';
                    $.each(err.response.data.errors, function (key, value) {
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';
                    $("#item_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        errorString + '</div>'
                    );
                });
        }
    });

});

$(".poin").click(function () {
    $(".register").toggle("hide");
    $(".login").toggle("show");
});

$(".forgot").click(function () {
    $(".login").toggle("hide");
    $(".reset").toggle("slow");
});

$(".log").click(function () {
    $(".reset").toggle("hide");
    $(".login").toggle("slow");
});

$("button[id^='set-state']").on("click", function (e) {
    var z = new FormData();
    z.append("stat", $(this).val());
    z.append("_token", $("input[name=_token]").val());

    var ajax = new XMLHttpRequest();
    var url = "changeLocation";
    ajax.open("POST", url, true);
    //ajax.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $(".dropbtn").html(data.locations);
            document.location = window.location.href;
        }
    };
    ajax.send(z);
});
$("#all_booking").on("click", ".edit_booking", function (e) {
    $("#booking_edit_msg").html("");
    var z = new FormData();
    z.append("id", $(this).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "editBooking";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#edit_booking").html(data.editForm);
            $("#bookingEdit").modal("show");
        }
    };
    ajax.send(z);
});

$("#phno").on("input propertychange paste", function () {
    if (isNaN($(this).val())) {
        var str = $(this).val();
        str = str.slice(0, -1);
        $(this).val(str);
    } else {
        $(".v-phn").html($(this).val());
        //$('buyer_phone').html($(this).val());
    }
});

var downloadTimer;

$("#edit-phno").on("click", function (e) {
    clearInterval(downloadTimer);
    $(".list-inline-item").removeClass("d-none");
    $(".cart-sub")
        .not(":eq(0)")
        .addClass("d-none");
    var z = new FormData();
    z.append("delete", "");
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "session_delete";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#step1").html(1);
            $("#step1")
                .addClass("cir")
                .removeClass("cir2 fa");
            $("#phno").prop("disabled", false);
            $("#send-vf-cod").prop("disabled", false);
            $(".fld-code").val("");
            $(".fld-code").prop("disabled", true);
            $("#first-next").prop("disabled", true);
            $("#resend").html(60);
        }
    };
    ajax.send(z);
});
$("#edit-address").on("click", function (e) {
    //$('#buyer-email').prop('disabled',false);
    $("#buyer-area").prop("disabled", false);
    $("#buyer-pin").prop("disabled", false);
    $("#buyer-name").prop("disabled", false);
    $("#flt-hus-ofc-no").prop("disabled", false);
    $("#str-soc-ofc-nam").prop("disabled", false);
    $("input[name=buyer_address_type]").prop("disabled", false);
    if ($("input[name='buyer_address_type']:checked").val() == "other") {
        $("#bu-ot-add").removeClass("d-none");
    }
    $("#secend-next").removeClass("d-none");
    $("#edit-address").addClass("d-none");
    $(".cart-sub")
        .eq(1)
        .addClass("d-none");
    $(".cart-sub")
        .eq(2)
        .addClass("d-none");
});
$("#edit-time").on("click", function (e) {
    $("input[name=deli-time]").prop("disabled", false);
    $("#deli-dat").prop("disabled", false);
    $("#third-next").removeClass("d-none");
    $("#edit-time").addClass("d-none");
    $(".cart-sub")
        .eq(2)
        .addClass("d-none");
});

$("#send-vf-cod").on("click", function (e) {
    e.preventDefault();
    if ($("#phno").val() != "") {
        var z = new FormData();
        z.append("numb", $("#phno").val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "smsSend";
        ajax.open("POST", url, true);
        ajax.setRequestHeader(
            "X-CSRF-TOKEN",
            $('meta[name="csrf-token"]').attr("content")
        );
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                if (data.result == "No Valid Numbers Found") {
                    alert(data.result + "\n\nPlease use anather Number");
                } else {
                    //alert(data.code);
                    $("#phno").prop("disabled", true);
                    $("#send-vf-cod").prop("disabled", true);
                    $(".fld-code").prop("disabled", false);
                    $("#first-next").prop("disabled", false);
                    var timeleft = 60;
                    downloadTimer = setInterval(function () {
                        if (timeleft <= 0) {
                            clearInterval(downloadTimer);

                            var z = new FormData();
                            z.append("delete", "");
                            z.append("_token", $("input[name=_token]").val());
                            var ajax = new XMLHttpRequest();
                            var url = "session_delete";
                            ajax.open("POST", url, true);
                            ajax.onreadystatechange = function () {
                                if (
                                    ajax.readyState == 4 &&
                                    ajax.status == 200
                                ) {
                                    var data = JSON.parse(ajax.responseText);
                                    alert(data.alert);
                                    $("#phno").prop("disabled", false);
                                    $("#send-vf-cod").prop("disabled", false);
                                    $(".fld-code").val("");
                                    $(".fld-code").prop("disabled", true);
                                    $("#first-next").prop("disabled", true);
                                }
                            };
                            ajax.send(z);
                        }
                        document.getElementById(
                            "resend"
                        ).innerHTML = timeleft--;
                    }, 1000);
                }
            }
        };
        ajax.send(z);
    }
});

$("#confurm-order").on("click", function () {
    $(this).html("Please Wait...");
    var z = new FormData();
    z.append("email", $("#buyer-email").val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "cashOnDelivery";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            // console.log(data);
            if (data.status == "success") {
                window.location = data.url;
            } else {
                alert(data.msg);
                $("#confurm-order").html("Confirm This Order");
            }
        }
    };
    ajax.send(z);
});

$("#add-package-order").on("click", function (e) {
    e.preventDefault();
    $("#add-package-order").html("Please Wait...");
    var z = new FormData();
    z.append("package_id", $("#pack_id").val());
    for (var i = 1; i <= $("input[id^='itm']").length; i++) {
        z.append("item_id[]", $("#itm" + i).val());
        z.append("item_total[]", $("#total_itm" + i).val());
        if ($("#itm" + i).prop("checked") == true) {
            z.append("status[]", 1);
        } else {
            z.append("status[]", 0);
        }
    }
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "add-package-cart";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.status == "success") {
                window.location = data.url;
            } else {
                alert(data.errors);
            }
        }
    };
    ajax.send(z);
});

$(".fld-code").keyup(function () {
    if (this.value.length == this.maxLength) {
        $(this)
            .parent(".list-inline-item")
            .next("li")
            .children(".fld-code")
            .val("");
        $(this)
            .parent(".list-inline-item")
            .next("li")
            .children(".fld-code")
            .focus();
    }
});

$("#first-next").on("click", function () {
    var z = new FormData();
    z.append("phone", $("#phno").val());
    z.append(
        "code",
        $("#cod1").val() +
        $("#cod2").val() +
        $("#cod3").val() +
        $("#cod4").val()
    );
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "varify_code";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.status == "success") {
                clearInterval(downloadTimer);
                $("#step1").html("");
                $("#step1")
                    .addClass("cir2 fa")
                    .removeClass("cir");
                $("#send-vf-cod").prop("disabled", true);
                $(".fld-code").prop("disabled", true);
                //$('.cart-sub').eq(1).show();
                $(".list-inline-item")
                    .not(":eq(0)")
                    .addClass("d-none");
                $(".cart-sub")
                    .eq(1)
                    .removeClass("d-none");
                if (data.delivery_address == true) {
                    $(".cart-sub")
                        .eq(2)
                        .removeClass("d-none");
                }
            } else {
                alert(data.alert);
            }
        }
    };
    ajax.send(z);
});

$("#secend-next").on("click", function (e) {
    $("#pin_suggation").html("");
    if ($("#buyer-name").val() == "") {
        $("#buyer-name").prop("required", true);
    } else if ($("#buyer-email").val() == "") {
        $("#buyer-email").prop("required", true);
    } else if ($("#buyer-pin").val() == "") {
        $("#buyer-pin").prop("required", true);
    } else if ($("#buyer-area").val() == "") {
        $("#buyer-area").prop("required", true);
    } else if ($("#flt-hus-ofc-no").val() == "") {
        $("#flt-hus-ofc-no").prop("required", true);
    } else if ($("#str-soc-ofc-nam").val() == "") {
        $("#str-soc-ofc-nam").prop("required", true);
    } else {
        if (
            $("#Home").prop("checked") == false &&
            $("#Office").prop("checked") == false &&
            $("#other").prop("checked") == false
        ) {
            $("input[name=buyer_address_type]").prop("required", true);
        } else {
            if (
                $("#other").prop("checked") == true &&
                $("#bu-ot-add").val() == ""
            ) {
                $("#bu-ot-add").prop("required", true);
            } else {
                e.preventDefault();
                var z = new FormData();
                z.append("buyer_pin", $("#buyer-pin").val());
                z.append("buyer_area", $("#buyer-area").val());
                z.append("flt_hus_ofc_no", $("#flt-hus-ofc-no").val());
                z.append("str_soc_ofc_nam", $("#str-soc-ofc-nam").val());
                z.append(
                    "buyer_address_type",
                    $("input[name='buyer_address_type']:checked").val()
                );
                z.append("buyer_address_other", $("#bu-ot-add").val());
                z.append("_token", $("input[name=_token]").val());
                var ajax = new XMLHttpRequest();
                var url = "addCustomer";
                ajax.open("POST", url, true);
                ajax.setRequestHeader(
                    "X-CSRF-TOKEN",
                    $('meta[name="csrf-token"]').attr("content")
                );
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        var data = JSON.parse(ajax.responseText);
                        if (data.status == "error") {
                            alert(data.msg);
                        } else {
                            $(".cart-sub")
                                .eq(1)
                                .removeClass("d-none");
                            $("#buyer-email").prop("disabled", true);
                            $("#buyer-area").prop("disabled", true);
                            $("#buyer-pin").prop("disabled", true);
                            $("#buyer-name").prop("disabled", true);
                            $("#flt-hus-ofc-no").prop("disabled", true);
                            $("#str-soc-ofc-nam").prop("disabled", true);
                            $("input[name=buyer_address_type]").prop(
                                "disabled",
                                true
                            );
                            $("#bu-ot-add").addClass("d-none");
                            $("#secend-next").addClass("d-none");
                            $("#edit-address").removeClass("d-none");
                        }
                    }
                };
                ajax.send(z);
            }
        }
    }
});
$("#third-next").on("click", function (e) {
    if (typeof $("input[name='deli-time']:checked").val() == "undefined") {
        $("input[name=deli-time]").prop("required", true);
    } else {
        e.preventDefault();
        var date = new Date();
        var mydate = new Date($("#deli-dat").val());

        if (date.setDate(date.getDate() + 2) > mydate) {
            alert(
                "The delivery date must be greater than " +
                date.getFullYear() +
                "-" +
                date.getMonth() +
                "-" +
                date.getDate()
            );
        } else {
            var z = new FormData();
            z.append("date", $("#deli-dat").val());
            z.append("time", $("input[name='deli-time']:checked").val());
            z.append("_token", $("input[name=_token]").val());
            var ajax = new XMLHttpRequest();
            var url = "storeDeliveryTime";
            //alert($('meta[name="csrf-token"]').attr('content'));
            ajax.open("POST", url, true);
            ajax.setRequestHeader(
                "X-CSRF-TOKEN",
                $('meta[name="csrf-token"]').attr("content")
            );
            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    $("input[name=deli-time]").prop("disabled", true);
                    $("#deli-dat").prop("disabled", true);
                    $("#edit-time").removeClass("d-none");
                    $("#third-next").addClass("d-none");
                    $(".cart-sub")
                        .eq(2)
                        .removeClass("d-none");
                }
            };
            ajax.send(z);
        }
    }
});

if ($(".cart-sub").length != 0) {
    $(".cart-sub")
        .not(":eq(0)")
        .addClass("d-none");
}

$(".tube").on("click", function (e) {
    var z = new FormData();
    z.append("id", $(".tube").val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "video_thumbnail";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#videoModal").modal("show");
            $("#all_video").html(data.videos);
        }
    };
    ajax.send(z);
});

$("#submitBooking").on("click", function (e) {
    e.preventDefault();
    $("#booking_alert").html(
        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>Please Wait...</div>'
    );
    var z = new FormData();
    z.append("booking_id", $("#book_id").val());
    z.append("name", $("#my_nm").val());
    z.append("email", $("#my_eml").val());
    z.append("phone", $("#my_pho").val());
    z.append("customer_qry", $("#my_qry").val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "book_now";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            $("#booking_alert").html(
                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                JSON.stringify(data) +
                "</div>"
            );
            var data = JSON.parse(ajax.responseText);
            if (data.msg) {
                $("form")
                    .find("input[id!=book_id], textarea")
                    .val("");
                $("#booking_alert").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );

                setTimeout(function () {
                    $("#bookingModal").modal("hide");
                    $("#booking_alert").html("");
                }, 3000);
            }
            if (data.errors.name) {
                $("#booking_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.name +
                    "</div>"
                );
            } else if (data.errors.email) {
                $("#booking_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.email +
                    "</div>"
                );
            } else if (data.errors.phone) {
                $("#booking_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.phone +
                    "</div>"
                );
            } else if (data.errors.query) {
                $("#booking_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.review +
                    "</div>"
                );
            } else {
                $("#booking_alert").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    JSON.stringify(data.errors) +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});

$("input[id^='itm']").on("click", function (e) {
    //alert($("input[id^='itm']").length);
    var id = $(this).attr("id");
    var num = $(this)
        .attr("id")
        .match(/\d+/g)
        .map(Number);

    var z = new FormData();
    z.append("package_id", $("#item_id").val());
    for (var i = 1; i <= $("input[id^='itm']").length; i++) {
        z.append("item_id[]", $("#itm" + i).val());
        z.append("item_total[]", $("#total_itm" + i).val());
        if ($("#itm" + i).prop("checked") == true) {
            z.append("status[]", 1);
        } else {
            z.append("status[]", 0);
        }
    }
    z.append("select_id", $(this).val());
    if ($(this).prop("checked") == true) {
        z.append("select_status", 1);
    } else {
        z.append("select_status", 0);
    }

    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "modify_package";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.price > 0) {
                $("#package_price").html(data.price);
            } else {
                $("#" + id).prop("checked", true);
            }
            if ($("#" + id).prop("checked") == false) {
                $("#plus" + num).prop("disabled", true);
                $("#minus" + num).prop("disabled", true);
                $("#total_itm" + num).val(1);

                $("#ws" + num).html(data.size_weight);
                $("#qnt" + num).html(data.quantaty);
            } else {
                $("#plus" + num).prop("disabled", false);
                $("#minus" + num).prop("disabled", false);
            }
        }
    };
    ajax.send(z);
});

$("button[id^='plus']").on("click", function (e) {
    var num = $(this)
        .attr("id")
        .match(/\d+/g)
        .map(Number);
    $("#total_itm" + num).val(Number($("#total_itm" + num).val()) + 1);
    $("#ws" + num).html(
        Number($("#size_weight" + num).val()) * $("#total_itm" + num).val()
    );
    $("#qnt" + num).html(
        Number($("#quantaty" + num).val()) * $("#total_itm" + num).val()
    );

    var z = new FormData();
    z.append("package_id", $("#item_id").val());
    for (var i = 1; i <= $("input[id^='itm']").length; i++) {
        z.append("item_id[]", $("#itm" + i).val());
        z.append("item_total[]", $("#total_itm" + i).val());

        if ($("#itm" + i).prop("checked") == true) {
            z.append("status[]", 1);
        } else {
            z.append("status[]", 0);
        }
    }
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "modify_package";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.price > 0) {
                $("#package_price").html(data.price);
            } else {
                $("#" + id).prop("checked", true);
            }
            if ($("#" + id).prop("checked") == false) {
                $("#plus" + num).prop("disabled", true);
                $("#minus" + num).prop("disabled", true);

                $("#ws" + num).html(data.size_weight);
                $("#qnt" + num).html(data.quantaty);
            } else {
                $("#plus" + num).prop("disabled", false);
                $("#minus" + num).prop("disabled", false);
            }
        }
    };
    ajax.send(z);
});
$("button[id^='minus']").on("click", function (e) {
    var num = $(this)
        .attr("id")
        .match(/\d+/g)
        .map(Number);
    if ($("#total_itm" + num).val() > 1) {
        $("#total_itm" + num).val(Number($("#total_itm" + num).val()) - 1);
    }

    $("#ws" + num).html(
        Number($("#size_weight" + num).val()) * $("#total_itm" + num).val()
    );
    $("#qnt" + num).html(
        Number($("#quantaty" + num).val()) * $("#total_itm" + num).val()
    );

    var z = new FormData();
    z.append("package_id", $("#item_id").val());
    for (var i = 1; i <= $("input[id^='itm']").length; i++) {
        z.append("item_id[]", $("#itm" + i).val());
        z.append("item_total[]", $("#total_itm" + i).val());

        if ($("#itm" + i).prop("checked") == true) {
            z.append("status[]", 1);
        } else {
            z.append("status[]", 0);
        }
    }
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "modify_package";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.price > 0) {
                $("#package_price").html(data.price);
            } else {
                $("#" + id).prop("checked", true);
            }
            if ($("#" + id).prop("checked") == false) {
                $("#plus" + num).prop("disabled", true);
                $("#minus" + num).prop("disabled", true);

                $("#ws" + num).html(data.size_weight);
                $("#qnt" + num).html(data.quantaty);
            } else {
                $("#plus" + num).prop("disabled", false);
                $("#minus" + num).prop("disabled", false);
            }
        }
    };
    ajax.send(z);
});

$("#add_pincode").on("click", function (e) {
    e.preventDefault();
    $("#pincode_msg").html(
        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>Please Wait...</div>'
    );

    var z = new FormData();

    z.append("locality", $("#locality").val());
    z.append("postOffice", $("#postOffice").val());
    z.append("pincode", $("#pincode").val());
    z.append("subDistrict", $("#subDistrict").val());
    z.append("district", $("#district").val());
    z.append("state", $("#state").val());

    if ($("#status").prop("checked") == true) {
        z.append("status", 1);
    } else {
        z.append("status", 0);
    }

    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "addPincode";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            //alert(JSON.stringify(ajax.responseText));
            var data = JSON.parse(ajax.responseText);
            if (data.msg) {
                $("#pincode_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("form")
                    .find(
                        "input[type=text], input[type=file], select, textarea"
                    )
                    .val("");

                //$('#all_pincode').html(data.pincodes);
                setTimeout(function () {
                    $("#postcodeAdd").modal("hide");
                    $("#pincode_msg").html("");
                    location.reload(true);
                }, 3000);
            } else if (data.errors.locality) {
                $("#pincode_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.locality +
                    "</div>"
                );
            } else if (data.errors.postOffice) {
                $("#pincode_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.postOffice +
                    "</div>"
                );
            } else if (data.errors.pincode) {
                $("#pincode_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.pincode +
                    "</div>"
                );
            } else if (data.errors.subDistrict) {
                $("#pincode_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.subDistrict +
                    "</div>"
                );
            } else if (data.errors.district) {
                $("#pincode_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.district +
                    "</div>"
                );
            } else if (data.errors.state) {
                $("#pincode_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.state +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});

$("#all_pincode").on("click", ".edit_pincode", function (e) {
    $("#pincode_edit_msg").html("");
    var z = new FormData();
    z.append("id", $(this).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "editPincode";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#edit_pincode").html(data.editForm);
            $("#postcodeEdit").modal("show");
        }
    };
    ajax.send(z);
});

$("#save_pincode").on("click", function (e) {
    e.preventDefault();
    $("#pincode_edit_msg").html(
        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>Please Wait...</div>'
    );

    var z = new FormData();
    z.append("id", $("#pin_id").val());
    z.append("locality", $("#pin_locality").val());
    z.append("postOffice", $("#pin_postOffice").val());
    z.append("pincode", $("#pin_pincode").val());
    z.append("subDistrict", $("#pin_subDistrict").val());
    z.append("district", $("#pin_district").val());
    z.append("state", $("#pin_state").val());

    if ($("#pin_status").prop("checked") == true) {
        z.append("status", 1);
    } else {
        z.append("status", 0);
    }

    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "savePincode";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            //alert(JSON.stringify(ajax.responseText));
            var data = JSON.parse(ajax.responseText);
            if (data.msg) {
                $("#pincode_edit_msg").html(
                    '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                    data.msg +
                    "</div>"
                );
                $("form")
                    .find(
                        "input[type=text], input[type=file], select, textarea"
                    )
                    .val("");

                setTimeout(function () {
                    $("#postcodeEdit").modal("hide");
                    $("#pincode_edit_msg").html("");
                    location.reload(true);
                }, 3000);
            } else if (data.errors.locality) {
                $("#pincode_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.locality +
                    "</div>"
                );
            } else if (data.errors.postOffice) {
                $("#pincode_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.postOffice +
                    "</div>"
                );
            } else if (data.errors.pincode) {
                $("#pincode_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.pincode +
                    "</div>"
                );
            } else if (data.errors.subDistrict) {
                $("#pincode_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.subDistrict +
                    "</div>"
                );
            } else if (data.errors.district) {
                $("#pincode_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.district +
                    "</div>"
                );
            } else if (data.errors.state) {
                $("#pincode_edit_msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.state +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});

$("#all_pincode").on("click", ".delete_pincode", function (e) {
    var r = confirm("Are You sure want to delete!");
    if (r == true) {
        var z = new FormData();
        z.append("id", $(this).val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "deletePincode";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                if (data.msg) {
                    alert(data.msg);
                    if (data.pincodes != "") {
                        $("#all_pincode").html("");
                        setTimeout(function () {
                            location.reload(true);
                        }, 3000);
                    }
                }
            }
        };
        ajax.send(z);
    }
});

$("#all_booking").on("click", ".status_booking", function (e) {
    var d = $(this)
        .val()
        .split("/");
    var z = new FormData();
    z.append("id", d[0]);
    z.append("status", d[1]);
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "statusBooking";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.bookings != "") {
                alert(data.msg);
                $("#all_booking").html(data.bookings);
            }
        }
    };
    ajax.send(z);
});

$("#login-form").submit(function (e) {
    e.preventDefault();
    $("#login_error").html("");
    var z = new FormData();
    z.append("email", $("#email").val());
    z.append("password", $("#password").val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = $(this).attr("action");
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            // alert(JSON.stringify(data));
            if (data.success == false) {
                $("#login_error").html(
                    "<p>" +
                    data.errors.email +
                    data.errors.password +
                    data.msg +
                    "</p>"
                );
            } else {
                location.reload(true);
                $("#login_error").html("<p>" + data.success + "</p>");
            }
        }
    };
    ajax.send(z);
});

$("#buyer-pin").on("input", function (e) {
    var x = $(this).val();
    $("#pin_suggation").html("");
    var z = new FormData();
    z.append("sr_con", $(this).val());
    z.append("_token", $("input[name=_token]").val());
    var url = "pinSuggation";
    var ajax;
    if (window.XMLHttpRequest) {
        ajax = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        ajax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (x.length > 1) {
        ajax.open("POST", url, true);
        ajax.setRequestHeader(
            "X-CSRF-TOKEN",
            $('meta[name="csrf-token"]').attr("content")
        );
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                $("#pin_suggation").html(data.suggation);
            }
        };
        ajax.send(z);
    }
});
$("ul").on("click", 'li[id^="pin_sg"]', function () {
    $("#buyer-pin").val($(this).html());
    $("#pin_suggation").html("");
});

$("#register").on("click", function () {
    $(".close").click();
    $("#open-modal").click();
});
$("#login").on("click", function () {
    $(".close").click();
    $("#modal-open").click();
});

$("#payWithCard").on("click", function () {
    $("#card-pay-msg").html(
        '<div class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Please Wait...</div>'
    );
    var z = new FormData();
    z.append("buyer_name", $("#buyer-name").val());
    z.append("buyer_phone", $("#phno").val());
    z.append("buyer_email", $("#buyer-email").val());
    z.append("_token", $("input[name=_token]").val());
    //alert($("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "buyer/payWithCard";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            //alert(JSON.stringify(data.url));
            //alert(data.url);
            if (data.result.success == true) {
                window.location = data.url;
            } else if (typeof data.errors.buyer_name != "undefined") {
                $("#card-pay-msg").html(
                    '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Error Alert!</strong> ' +
                    data.errors.buyer_name +
                    "</div>"
                );
            } else if (typeof data.errors.buyer_phone != "undefined") {
                $("#card-pay-msg").html(
                    '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Error Alert!</strong> ' +
                    data.errors.buyer_phone +
                    "</div>"
                );
            } else if (typeof data.errors.buyer_email != "undefined") {
                $("#card-pay-msg").html(
                    '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Error Alert!</strong> ' +
                    data.errors.buyer_email +
                    "</div>"
                );
            } else if (typeof data.error != "undefined") {
                $("#card-pay-msg").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.error +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});

$("#all_order").on("click", ".show_invoice", function (e) {
    var z = new FormData();
    z.append("id", $(this).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "showInvoice";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#show_invoice").html(data.invoice);
            $("#orderInvoice").modal("show");
        }
    };
    ajax.send(z);
});
$('select[id^="pay_stat"]').on("change", function (e) {
    var r = confirm(
        "Are You sure want to set the payment status as " + $(this).val() + " !"
    );
    var id = $(this).attr("id");
    if (r == true) {
        var z = new FormData();
        z.append("id", this.id.match(/\d+/));
        z.append("payment_status", $(this).val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "paymentStatus";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                if (data.status == "Success") {
                    alert(data.msg);
                }
            }
        };
        ajax.send(z);
    } else {
        if ($(this).val() == "Completed") {
            $("#" + id)
                .val("Pending")
                .prop("selected", true);
        } else {
            $("#" + id)
                .val("Completed")
                .prop("selected", true);
        }
    }
});

$('select[id^="ship_stat"]').on("change", function (e) {
    var r = confirm(
        "Are You sure want to set the payment status as " + $(this).val() + " !"
    );
    var id = $(this).attr("id");
    if (r == true) {
        var z = new FormData();
        z.append("id", this.id.match(/\d+/));
        z.append("shipping_status", $(this).val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "shippingStatus";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                if (data.status == "Success") {
                    alert(data.msg);
                }
            }
        };
        ajax.send(z);
    } else {
        var z = new FormData();
        z.append("id", this.id.match(/\d+/));
        z.append("shipping_status", "");
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "shippingStatus";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                $("#" + id)
                    .val(data.status)
                    .prop("selected", true);
            }
        };
        ajax.send(z);
    }
});

$('select[id^="user_stat"]').on("change", function (e) {
    var r = confirm(
        "Are You sure want to set the roll as " + $(this).val() + " !"
    );
    var id = $(this).attr("id");
    if (r == true) {
        var z = new FormData();
        z.append("id", this.id.match(/\d+/));
        z.append("user_roll", $(this).val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "userRoll";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                if (data.status == "Success") {
                    alert(data.msg);
                }
            }
        };
        ajax.send(z);
    } else {
        var z = new FormData();
        z.append("id", this.id.match(/\d+/));
        z.append("user_roll", "");
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "userRoll";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                $("#" + id)
                    .val(data.status)
                    .prop("selected", true);
            }
        };
        ajax.send(z);
    }
});

$("#look_for").on("keypress", function (event) {
    var key = event.which || event.keyCode;
    if (key == 9 || key == 39) {
        if (document.getElementById("look_for_auto").value != "") {
            document.getElementById("look_for").value = document.getElementById(
                "look_for_auto"
            ).value;
            document.getElementById("look_for_auto").value = "";
            document.getElementById("Suggestion").innerHTML = "";
        }
    }
});
// $("#look_for").on("input", function(e) {
//     var x = $(this).val();
//     $("#look_for_auto").val("");
//     $("#Suggestion").html("");
//     var z = new FormData();
//     z.append("sr_con", $(this).val());
//     z.append("_token", $("input[name=_token]").val());
//     var url = "searchSuggation";
//     var ajax;
//     if (window.XMLHttpRequest) {
//         ajax = new XMLHttpRequest();
//     } else {
//         // code for IE6, IE5
//         ajax = new ActiveXObject("Microsoft.XMLHTTP");
//     }
//     if (x.length > 1) {
//         ajax.open("POST", url, true);
//         ajax.setRequestHeader(
//             "X-CSRF-TOKEN",
//             $('meta[name="csrf-token"]').attr("content")
//         );
//         ajax.onreadystatechange = function() {
//             if (ajax.readyState == 4 && ajax.status == 200) {
//                 var data = JSON.parse(ajax.responseText);
//                 $("#look_for_auto").val(data.look);
//                 $("#Suggestion").html(data.suggation);
//             }
//         };
//         ajax.send(z);
//     }
// });

$(".expand").on("click", function () {
    $(this)
        .next()
        .slideToggle(400);
    $expand = $(this).find(">:first-child");
    if ($expand.html() == '<i class="fa fa-chevron-right fa-lg"></i>') {
        $expand.html('<i class="fa fa-chevron-down fa-lg"></i>');
    } else {
        $expand.html('<i class="fa fa-chevron-right fa-lg"></i>');
    }
});



if ($('ul[id^="flexisel"]').length != 0) {
    $(document).ready(function () {
        for (var i = 1; i <= $('ul[id^="flexisel"]').length; i++) {
            //alert(i);
            $("#flexisel" + i).flexisel({
                visibleItems: 6,
                itemsToScroll: 1,
                autoPlay: {
                    enable: true,
                    interval: 5000,
                    pauseOnHover: true
                }
            });
        }
    });
}

if ($(".accordian").length != 0) {
    $(document).ready(function () {
        $(".accordian h3 i").click(function (e) {
            $(this).toggleClass("openn");
            $(".accordian ul ul").slideUp();
            if (
                $(this)
                    .parent()
                    .next()
                    .is(":hidden")
            ) {
                $(this)
                    .parent()
                    .next()
                    .slideDown();
                $(this)
                    .child()
                    .toggleClass("openn");
            }
        });
    });

    $(document).ready(function () {
        $(".accordian h4").click(function (e) {
            $("accordian h4")
                .find(".fa.fa-plus openn")
                .toggleClass("openn");
            $(
                $(e.target)
                    .find(".fa.fa-plus")
                    .toggleClass("openn")
            );

            $(".accordian ul ul ul").slideUp();
            if (
                $(this)
                    .next()
                    .is(":hidden")
            ) {
                $(this)
                    .next()
                    .slideDown();
            }
        });
    });
}

$("#cal_comm").on("click", function () {
    var z = new FormData();
    z.append("seller", $("#seller_name").val());
    z.append("start_date", $("#start_date").val());
    z.append("end_date", $("#end_date").val());
    z.append("_token", $("input[name=_token]").val());

    var ajax = new XMLHttpRequest();
    var url = "commition";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            //alert(JSON.stringify(data));
            if (data.commition) {
                //$('#sellerCommition').html('');
                //$('form').find("input, select").val('');
                $("#sellerCommition").html(data.commition);
            } else if (data.errors.seller) {
                $("#sellerCommition").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.seller +
                    "</div>"
                );
            } else if (data.errors.start_date) {
                $("#sellerCommition").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.start_date +
                    "</div>"
                );
            } else if (data.errors.end_date) {
                $("#sellerCommition").html(
                    '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                    data.errors.end_date +
                    "</div>"
                );
            }
        }
    };
    ajax.send(z);
});

$("#all_order").on("click", ".delete_order", function (e) {
    var r = confirm("Are You sure want to delete!");
    if (r == true) {
        var z = new FormData();
        z.append("id", $(this).val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "deleteOrder";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                if (data.status == "success") {
                    $("#all_order").html(data.orders);
                } else {
                    alert(data.error);
                }
            }
        };
        ajax.send(z);
    }
});

$("#page_nam").on("change", function () {
    //var editor = CKEDITOR.instances.fck;
    var z = new FormData();
    z.append("name", $(this).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "showContent";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.status == "success") {
                CKEDITOR.instances.content.setData(data.content);
            } else {
                CKEDITOR.instances.content.setData("");
            }
        }
    };
    ajax.send(z);
});

$("#blog_id").on("change", function () {
    $("#alert").html("");
    var z = new FormData();
    z.append("id", $(this).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "showBlog";
    ajax.open("POST", url, true);
    ajax.setRequestHeader(
        "X-CSRF-TOKEN",
        $('meta[name="csrf-token"]').attr("content")
    );
    ajax.onreadystatechange = function () {
        //alert(ajax.status);
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            if (data.status == "success") {
                $("#btn-blog").html(
                    '<button type="button" id="blog_btn_del" class="btn btn-info">Delete</button>'
                );
                $("#heading").val(data.heading);
                $("#published").val(data.published);
                $("#show-img").html(data.image);
                CKEDITOR.instances.content.setData(data.content);
            } else {
                $("#btn-blog").html("");
                $("#heading").val("");
                $("#published").val("");
                $("#show-img").html("");
                CKEDITOR.instances.content.setData("");
            }
        }
    };
    ajax.send(z);
});

$("#btn-blog").on("click", "#blog_btn_del", function () {
    var r = confirm("Are you sure you want to delete?");
    if (r == true) {
        var z = new FormData();
        z.append("id", $("#blog_id").val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "deleteBlog";
        ajax.open("POST", url, true);
        ajax.setRequestHeader(
            "X-CSRF-TOKEN",
            $('meta[name="csrf-token"]').attr("content")
        );
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                if (data.status == "success") {
                    $("#btn-blog").html("");
                    $("#heading").val("");
                    $("#published").val("");
                    $("#show-img").html("");
                    CKEDITOR.instances.content.setData("");
                    $("#alert").html(
                        '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Error Alert!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                    setTimeout(function () {
                        location.reload(true);
                    });
                } else {
                    $("#alert").html(
                        '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Error Alert!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                }
            }
        };
        ajax.send(z);
        $.ajax({
            type: "POST",
            url: "deleteTermAttribute",
            data: {
                id: $("#term_id").val(),
                attribute_id: $("#choose_attribute_id").val(),
                _token: $("input[name=_token]").val()
            },
            success: function (data) {
                // What to do if we error
                if (data.errors) {
                    $("#attr_msg").html(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> ' +
                        data.errors +
                        "</div>"
                    );
                } else {
                    $("#term_id").empty();
                    $("#term_id").append(
                        '<option value="">Select any Term</option>'
                    );
                    $.each(data.terms, function (key, val) {
                        $("#term_id").append(
                            '<option value="' +
                            val.id +
                            '">' +
                            val.name +
                            "</option>"
                        );
                    });

                    $("#attr_btn_third").html(
                        '<button type="button" class="btn btn-primary" id="create_term">Add Term</button>'
                    );
                    $("#attr_btn_forth").html("");
                    $("#attr_msg").html(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> ' +
                        data.msg +
                        "</div>"
                    );
                    $("#set_term_name").val("");
                }
            }
        });
    }
});

window.openAddress = function () {
    $(".cart-sub")
        .eq(1)
        .removeClass("d-none");
};
window.myFunction = function () {
    $("#bu-ot-add").val("");
    if (document.getElementById("other").checked) {
        $("#bu-ot-add").removeClass("d-none");
    } else if (document.getElementById("Home").checked) {
        $("#bu-ot-add").addClass("d-none");
        $("#bu-ot-add").prop("required", false);
    } else if (document.getElementById("Office").checked) {
        $("#bu-ot-add").addClass("d-none");
        $("#bu-ot-add").prop("required", false);
    }
};
//proImgMain(\'product_image_carousel'.$id.'\',this.id)
window.proImgMain = function (a, b) {
    var z = new FormData();
    z.append("id", $("#" + b).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "mainProductImg";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#" + a).html(data.proImg);
        }
    };
    ajax.send(z);
};
//delProImg(\'product_image_carousel'.$id.'\',\'product_image_activ'.$img->id.'\')
window.delProImg = function (a, b) {
    var z = new FormData();
    z.append("id", $("#" + b).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "deleteProductImg";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#" + a).html(data.proImg);
        }
    };
    ajax.send(z);
};
//packImgMain(\'package_image_carousel'.$id.'\',\'package_image_activ'.$image->id.'\')
window.packImgMain = function (a, b) {
    var z = new FormData();
    z.append("id", $("#" + b).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "mainPackageImg";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#" + a).html(data.packImg);
        }
    };
    ajax.send(z);
};
//delPackImg(\'package_image_carousel'.$id.'\',\'package_image_activ'.$image->id.'\')
window.delPackImg = function (a, b) {
    var z = new FormData();
    z.append("id", $("#" + b).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "deletePackageImg";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#" + a).html(data.packImg);
        }
    };
    ajax.send(z);
};
window.bookImgMain = function (a, b) {
    var z = new FormData();
    z.append("id", $("#" + b).val());
    z.append("_token", $("input[name=_token]").val());
    var ajax = new XMLHttpRequest();
    var url = "mainBookingImg";
    ajax.open("POST", url, true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $("#" + a).html(data.bookIng);
        }
    };
    ajax.send(z);
};

window.delBookImg = function (a, b) {
    var r = confirm("Are You sure want to delete!");
    if (r == true) {
        var z = new FormData();
        z.append("id", $("#" + b).val());
        z.append("_token", $("input[name=_token]").val());
        var ajax = new XMLHttpRequest();
        var url = "deleteBookingImg";
        ajax.open("POST", url, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                $("#" + a).html(data.bookIng);
            }
        };
        ajax.send(z);
    }
};

//showTerm(this.id,'trrm_td1')
window.showTerm = function (a, b) {
    $("#" + b).html("");
    var num;
    var suffix = $("#" + a)
        .attr("id")
        .match(/(\d+)/g);
    var i = 0;
    for (i = 0; i < suffix.length; i++) {
        num = suffix[i];
    }
    if ($("#" + a).attr("id") == "attribute_id" + num) {
        var combo = $("<select multiple></select>")
            .attr("id", "term_id" + num)
            .attr("name", "term_id[]")
            .addClass("form-control multipleSelect");
    } else {
        var combo = $("<select multiple></select>")
            .attr("id", num + "term_id")
            .attr("name", "term_id[]")
            .addClass("form-control multipleSelect");
    }

    if ($("#" + a).val() != "") {
        $.get("attribute/" + $("#" + a).val(), function (data) {
            $.each(data, function (index, subcatObj) {
                combo.append(
                    '<option value="' + subcatObj.attribute_id + '-' +
                    subcatObj.id +
                    '">' +
                    subcatObj.name +
                    "</option>"
                );
            });
        });
    }
    //return combo;
    // OR
    $("#" + b).html(combo);
    $(".multipleSelect").fastselect();
};
window.singleProduct = function (a) {
    window.location.href = $("#" + a).val();
};
//shortBy(this.id)
window.shortBy = function (a) {
    var d = window.location.href.split("/");
    if (d[2] == "localhost") {
        var c = d[5];
        var s = d[6];
    } else {
        var c = d[4];
        var s = d[5];
    }
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    $.ajax({
        type: "POST",
        url: "search",
        data: {
            cate: c,
            sub_cate: s,
            short: $("#" + a).val(),
            _token: $("input[name=_token]").val()
        },
        success: function (data) {
            //alert(JSON.stringify(data.bookings));
            $("#filter-product").html(data.products);
            $("#filter-package").html(data.packages);
            $("#filter-booking").html(data.bookings);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // What to do if we fail
            // console.log(JSON.stringify(jqXHR));
            //console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
};

$(document).ready(function () {
    if ($("#start_date").length != 0) {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = "0" + dd;
        }
        if (mm < 10) {
            mm = "0" + mm;
        }

        today = yyyy + "-" + mm + "-" + dd;
        $("#start_date, #end_date").attr("max", today);
    }
    $('[data-toggle="tooltip"]').tooltip();
});
var segment_str = window.location.pathname; // return segment1/segment2/segment3/segment4
var segment_array = segment_str.split("/");
var last_segment = segment_array[segment_array.length - 1];
if (last_segment == "pages" || last_segment == "blog") {
    CKEDITOR.replace("content");
} // alerts segment4
/*---------------- PRINT START --------------------------------------*/
$("#print_me").click(function () {
    var contents = $("#show_invoice").html();
    var frame1 = $("<iframe />");
    frame1[0].name = "frame1";
    frame1.css({ position: "absolute", top: "-1000000px" });
    $("body").append(frame1);
    var frameDoc = frame1[0].contentWindow
        ? frame1[0].contentWindow
        : frame1[0].contentDocument.document
            ? frame1[0].contentDocument.document
            : frame1[0].contentDocument;
    frameDoc.document.open();
    //Create a new HTML document.
    frameDoc.document.write("<html><head><title>DIV Contents</title>");
    frameDoc.document.write("</head><body>");
    //Append the external CSS file.
    frameDoc.document.write(
        '<link href="/css/app.css" rel="stylesheet" type="text/css" />'
    );
    //Append the DIV contents.
    frameDoc.document.write(contents);
    frameDoc.document.write("</body></html>");
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();
    }, 500);
});

$("#att_category_id").on("change", function () {
    var cat_id = $(this).val();
    $("#att_sub_category_id").empty();
    $("#att_sub_category_id").append(
        '<option value="">Select Subcategory</option>'
    );
    $.get("category/" + cat_id, function (data) {
        //success data
        $.each(data.subcategories, function (key, val) {
            $("#att_sub_category_id").append(
                '<option value="' +
                val.id +
                '">' +
                val.sub_category_name +
                "</option>"
            );
        });
    });
});
$("#att_sub_category_id").on("change", function () {
    var cat_id = $("#att_category_id").val();
    var subId = $("#att_sub_category_id").val();
    $("#choose_attribute_id").empty();
    $("#choose_attribute_id").append(
        '<option value="">Select Attribute</option>'
    );
    $.get("getatt/" + cat_id + "/" + subId, function (data) {
        //success data
        // console.log(data.option)
        $("#choose_attribute_id").append(data.option);
    });
});

$("#sub_category_id").on("change", function () {
    $("#ready").html("");
    $("#total-attribute").html('0');
    $("#moreAttribute > tbody").empty();
    $("#ready").html(localStorage.oldData);
    // console.log(localStorage.oldData)
    // $('#ready').reset()
    var cat_id = $("#category_id").val();
    var subId = $("#sub_category_id").val();
    $("#attribute_id1").empty();
    $("#attribute_id1").append('<option value="">Select Attribute</option>');
    $.get("getatt/" + cat_id + "/" + subId, function (data) {
        //success data
        // console.log(data.option)
        $("#attribute_id1").append(data.option);
    });
});

/*------------------ PRINT END -------------------------------------*/

/*---------------- CALANDER START ------------------------------------*/

/*---------------- CALANDER END -------------------------------------*/
/*if($('#myInput').length != 0){
    $(document).ready(function(){
    });
}*/
/*$('#set-state').on('change', function(){
        var z = new FormData();
        z.append('stat', $(this).val());
        z.append('_token', $("input[name=_token]").val());

        var ajax = new XMLHttpRequest();
        var url = "changeLocation";
        ajax.open("POST", url, true);
        //ajax.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        ajax.onreadystatechange=function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                var data = JSON.parse(ajax.responseText);
                $('.dropbtn').html(data.locations);
                document.location = window.location.href;
            }
        }
        ajax.send(z);
});*/
/*$('#buyer-pin').on('blur', function(){
    $('#pin_suggation').html('');
});*/
/*$('#pay-with-paypal').on('click', function(){
    window.location = 'pay-with-paypal/'+$("input[name='deli-time']:checked").val();
});*/
/*$('#register-form').submit(function (event){
    event.preventDefault();
  var results = '';
  jQuery('#register_error').html('');
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: {name: $("#name").val(), role: $("#role").val(), email: $("#email").val(), phone: $("#phone").val(), password: $("#password").val(), password_confirmation: $("#password-confirm").val()},
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        var response = JSON.parse(response);
        if(response.success == false){
            jQuery.each(response.errors, function(key, value){
            jQuery('#register_error').show();
            jQuery('#register_error').append('<p>'+value+'</p>');
        });
        } else {
            window.location.href = response.redirectto;
        }
      }, error: function (xhr, status, error) {
        jQuery('#register_error').show();
        jQuery('#register_error').append('<div class="alert alert-warning alert-dismissible">  <button type="button" class="close" data-dismiss="alert">&times;</button>  <strong>Error!</strong> '+error+'</div>');

      }
  });
});
*/
/*$('#add-more-item').on('click', function(e){

    $("#add-more-item").html('Please Wait...');

    var z = new FormData();
    z.append('total_item', $('#total-item').html());
    z.append('_token', $("input[name=_token]").val());

    var ajax = new XMLHttpRequest();
    var url = "addMore";
    ajax.open("POST", url, true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);

            $('#total-item').html(data.totalItem);
            $('#moreItem tbody').append(data.more);
            $("#add-more-item").html('Add More');
        }
    }
    ajax.send(z);
});
$('#remove-more-item').on('click', function(e){
    if(parseInt($('#total-item').html())>1){
        $('#total-item').html(parseInt($('#total-item').html())-1);
        $('#moreItem tr:last').remove();
    }
});*/
/*$('#packageEdit').on('click', '#more-item-add', function(e){
    $("#more-item-add").html('Please Wait...');
    var z = new FormData();
    z.append('item_total', $('#item_total').html());
    z.append('_token', $("input[name=_token]").val());

    var ajax = new XMLHttpRequest();
    var url = "moreProAdd";
    ajax.open("POST", url, true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var data = JSON.parse(ajax.responseText);
            $('#item_total').html(data.totalItem);
            $('#itemMore tbody').append(data.more);
            $("#more-item-add").html('Add More');
        }
    }
    ajax.send(z);
});

$('#packageEdit').on('click', '#more-item-remove', function(e){
    if(parseInt($('#item_total').html())>1){
        if($('#samogri_id'+parseInt($('#item_total').html())).val()==''){
            $('#item_total').html(parseInt($('#item_total').html())-1);
            $('#itemMore tr:last').remove();
        }
    }
});

$('#packageEdit').on('click', '.delete_pack_pro', function(e){
    var r = confirm("Are You sure want to delete!");
    if(r == true){
        if(parseInt($('#item_total').html()) > 1){
            var z = new FormData();
            z.append('id', $(this).val());
            z.append('_token', $("input[name=_token]").val());
            var ajax = new XMLHttpRequest();
            var url = "deletePrdPackage";
            ajax.open("POST", url, true);
            ajax.onreadystatechange=function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    var data = JSON.parse(ajax.responseText);
                    $('#item_total').html(data.totalItem);
                    $('#itemMore').html(data.more);
                }
            }
            ajax.send(z);
        }else{
            alert('Last Product of this Package are not allow to Delete!');
        }

    }
});*/
/*$('#brnd_btn_first').on('click', '#create_brand', function(){

    $("#brnd_msg").html('<div class="alert alert-danger alert-dismissable">Please Wait...</div>');

    $.ajax({
        type: "POST",
        url: "addBrand",
        data: {
            'name': $('#set_brand_name').val(),
            '_token': $("input[name=_token]").val(),
        },
        success: function(data){
            if((data.errors)){
                $("#brnd_msg").html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+data.errors.name+'</div>');
            }else{

                $('#choose_brand_id').empty();
                $('#choose_brand_id').append('<option value="">Select any Brand</option>');
                $.each(data.brands, function(key, val){
                   $('#choose_brand_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });


                $("#brnd_msg").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> '+data.msg+'</div>');
                $("#set_brand_name").val('');
            }
        }
    });
});
$('#choose_brand_id').on('change', function(){
    var brnd_id = $(this).val();
    if(brnd_id==''){
        $('#set_brand_name').val('');
        $('#brnd_btn_first').html('<button type="button" class="btn btn-primary" id="create_brand">Add Brand</button>');
        $('#brnd_btn_secend').html('');
    }else{
        $('#set_brand_name').val($('#choose_brand_id option:selected').text());
        $('#brnd_btn_first').html('<button type="button" class="btn btn-primary" id="update_brand">Save Brand</button>');
        $('#brnd_btn_secend').html('<button type="button" class="btn btn-primary" id="delete_brand">Delete Brand</button>');
    }
});
$('#brnd_btn_first').on('click', '#update_brand', function(){
    $("#brnd_msg").html('<div class="alert alert-danger alert-dismissable">Please Wait...</div>');
    $.ajax({
        type: "POST",
        url: "saveBrand",
        //data: z,
        data: {
            'id': $('#choose_brand_id').val(),
            'name': $('#set_brand_name').val(),
            '_token': $("input[name=_token]").val(),
        },
        success: function(data){ // What to do if we error
            if((data.errors)){
                $("#brnd_msg").html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+data.errors+'</div>');
            }else{

                $('#choose_brand_id').empty();
                $('#choose_brand_id').append('<option value="">Select any Brand</option>');
                $.each(data.brands, function(key, val){
                   $('#choose_brand_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });


                $('#brnd_btn_first').html('<button type="button" class="btn btn-primary" id="create_brand">Add Brand</button>');
                $('#brnd_btn_secend').html('');

                 $("#brnd_msg").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> '+data.msg+'</div>');
                $("#set_brand_name").val('');

            }
        }
    });
});
$('#brnd_btn_secend').on('click', '#delete_brand', function(){
    var r = confirm("Clicking this check box will remove all Products and Events. Are you sure you want to continue?");
    if(r == true){
        $("#brnd_msg").html('<div class="alert alert-danger alert-dismissable">Please Wait...</div>');

        $.ajax({
            type: "POST",
            //enctype: 'multipart/form-data',
            url: "deleteBrand",
            //data: z,
            data: {
                'id': $('#choose_brand_id').val(),
                '_token': $("input[name=_token]").val(),
            },
            success: function(data){ // What to do if we error
                if((data.errors)){
                    $("#brnd_msg").html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+data.errors+'</div>');
                }else{

                    $('#choose_brand_id').empty();
                    $('#choose_brand_id').append('<option value="">Select any Brand</option>');
                    $.each(data.brands, function(key, val){
                       $('#choose_brand_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                    });

                    $("#brnd_msg").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> '+data.msg+'</div>');
                    $("#set_brand_name").val('');
                }
            }
        });
    }
});*/
/*$(document).ready(function(){
    $('.carous').each(function(){
        if($(this).width() < $(this).children('ul').width()){
            $(this).children('carrow').each(function(){
                $(this).hide();
            });
        }
    });

    $('.carous').hover(function(){
        $(this).children('.carrow').each(function(){
            $(this).addClass('carrow-hover');
        });
    }, function(){
        $(this).children('.carrow').each(function(){
            $(this).removeClass('carrow-hover');
        });
    });

    $('.carrow').hover(function(){myAnimate(this)}, function(){
        $(this).parent().children('ul').stop();
    });
});
function myAnimate(that){
        var SD = 210;
        var $carous = $(that).parent();
        var $ul = $carous.children('ul');
        var distance = SD;
        var time = 2500;
        var rate = distance/time;
        distance = Math.abs($ul.position().left);
        if($(that).hasClass('left-arrow')){
            if(distance == 0) {
                $ul.css({left: -210});
                $ul.prepend($ul.children('li:last-child'));
            } else {
                time = distance/rate;
            }
            $ul.stop().animate({
                left: 0
            }, time, 'linear', function(){myAnimate(that)});
        }
        else if($(that).hasClass('right-arrow')){
            if(distance != 0){
                distance = SD - distance;

                time = distance/rate;
            }
            $ul.stop().animate({
                left: -210
            }, time, 'linear', function(){
                $ul.append($ul.children('li:first-child'));
                $ul.css({left: 0});
                myAnimate(that);
            });
        }
}*/
