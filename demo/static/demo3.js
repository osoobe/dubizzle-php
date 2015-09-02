function build_options($selector, $){
    $selector.empty();
}

function build_wait(){
    $("#live_results").html("<img src='http://www.keter.com/files/images/"
                            +"global/themes/2AC7D0/product__showcase__loading"
                            +"_animation.gif' /></h4>");
}

function build_results(data){
    var models = JSON.parse(data);
    var $article_holder = $("#live_results");
    if(models.length > 0){
        var articles = "";
        for(x in models){
            articles += "<article><hr/>"
                     + "<img class='car-phto' src='" + models[x].photo + "'/>"
                     + "<p>Car: <a href='" + models[x].url + "'>"
                     + models[x].title + "</a></p>"
                     + "<p>Price: " + models[x].price + "</p>"
                     + "<p>Currency: " + models[x].currency + "</p>"
                     + "<p>Category: " + models[x].category + "</p><hr/></article>";
        }
        $article_holder.empty();
        $article_holder.append(articles);
    }else{
        $article_holder.html("<h4>No result was found</h4>");
    }
}


$(document).on("click", ".form-selector", function(){
    var $this = $(this);
    $(".form-selector").removeClass("active");
    $(".form-capture").removeClass("active");
    $this.addClass("active");
    $("" + $this.attr("href")).addClass("active");
});

$(document).on("change", "select#make", function(){
    var $this = $(this);
    var brand = $this.val();
    $.get("get_params.php", {"make": brand, "type": "model"}, function(data){
        var $model_selector = $("select#model");
        $model_selector.empty();
        var option = "";
        var jdata = JSON.parse(data);
        for(x in jdata){
            key = jdata[x][1];
            value = jdata[x][0];
            option += "<option value='" + value + "'>" + key + "</option>";
        }
        $model_selector.removeAttr("disabled");
        $model_selector.append(option);
    });
});


$(document).on("change", "select#model", function(){
   $("select#year").removeAttr("disabled");
   $("button#first-btn").removeAttr("disabled");
});


$(document).on("click", "button#first-btn", function(){
    var make = $("select#make").val();
    var model = $("select#model").val();
    var year = $("select#year").val();

    $.get("get_params.php", {"make": make, "model": model,
                             "type": "body_type"}, function(data){
        var $model_selector = $("select#body_type");
        $model_selector.empty();
        var option = "";
        var jdata = JSON.parse(data);
        for(x in jdata){
            value = jdata[x]["id"];
            key = jdata[x]["name"];
            option += "<option value='" + value + "'>" + key + "</option>";
        }
        $model_selector.removeAttr("disabled");
        $model_selector.append(option);
    });

    build_wait();
    $.get("json_search.php", {"make": make, "model": model,
                                "country":'uae',
                                "city":'dubai',
                                "section":'motors',
                                "category": 'cars',
                              "min_year": year, "max_year": year}, function(data){
        build_results(data);
        $("select").removeAttr("disabled");
    });

    $(".form-selector").removeClass("active");
    $(".form-capture").removeClass("active");
    $(".form-selector[href='#select_model']").addClass("active");
    $("#select_model").addClass("active");
});


$(document).on("change", "select#body_type", function(){
    var $this = $(this);
    var body_type = $this.val();
    var make = $("select#make").val();
    var model = $("select#model").val();
    var year = $("select#year").val();

    build_wait();
    $.get("json_search.php", {"make": make, "model": model, "body_type": body_type,
                              'min_kms': max_kms, 'min_kms': min_kms,
                                "country":'uae',
                                "city":'dubai',
                                "section":'motors',
                                "category": 'cars',
                              "min_year": year, "max_year": year}, function(data){
        build_results(data);
    });
});


$(document).on("click", "#second-btn", function(){
    var kilo = $("select#kilometers").val().split("-");
    var make = $("select#make").val();
    var body_type = $("select#body_type").val();
    var model = $("select#model").val();
    var year = $("select#year").val();
    var min_kms = kilo[0];
    var max_kms = kilo[1];

    build_wait();
    $.get("json_search.php", {"make": make, "model": model, "body_type": body_type,
                              'min_kms': max_kms, 'min_kms': min_kms,
                                "country":'uae',
                                "city":'dubai',
                                "section":'motors',
                                "category": 'cars',
                              "min_year": year, "max_year": year}, function(data){
        build_results(data);
    });
});
