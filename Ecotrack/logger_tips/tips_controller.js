var tipsToUpload = [];

class Tip {
    constructor(tip, description, link1, link2, subject, image){
        this.tip = tip;
        this.desc = description;
        this.link1 = link1;
        this.link2 = link2;
        this.subject = subject;
        this.image = image;
    }
}

$(document).ready(function () {
    if(badSubjects.length > 0){
        $.ajax({
            type: "get",
            url: "allTips.js",
            dataType: "json",
        })
        .then(function(responseData) {
            console.log("First AJAX response:", responseData);
        
            var allTipsData = responseData.allTips;
            for (var i = 0; i < badSubjects.length; i++) {
                // Find the object matching the current bad subject
                var badSubjectObj = allTipsData.find(function(item) {
                    return item.subject === badSubjects[i];
                });
        
                if (badSubjectObj) {
                    var tip = badSubjectObj['tip'];
                    var desc = badSubjectObj['description'];
                    var link1 = badSubjectObj['links'][0];
                    var link2 = badSubjectObj['links'][1];
                    var subject = badSubjectObj['subject'];
                    var image = badSubjectObj['image'];
        
                    console.log("Tip:", tip);
                    console.log("Description:", desc);
                    console.log("Links:", link1, link2);

                    const t100 = new Tip(tip, desc, link1, link2, subject, image);
                    tipsToUpload.push(t100);

                } else {
                    console.log("No match found for subject:", badSubjects[i]);
                }
            }

            if(tipsToUpload.length > 0){
                for(var i = 0; i < tipsToUpload.length; i++){
                    var tipOutput = '';
                    // tipOutput += "<div class=\"tip-container-" + (i+1) + "\">";
                    var h1Output = '';
                    let originalString = tipsToUpload[i].subject;
                    let titleizedString = originalString
                        .replace(/_/g, ' ')        // Replace underscores with spaces
                        .replace(/\b\w/g, c => c.toUpperCase()); // And capitalize
                    if(i==0){
                        h1Output += "First Tip: " + titleizedString;
                    }
                    else if(i==1){
                        h1Output += "Second Tip: " + titleizedString;
                    }
                    else if(i==2){
                        h1Output += "Third Tip: " + titleizedString;
                    }
                    tipOutput += '<h1>' + h1Output + '</h1>';
                    tipOutput += "<h2>" + tipsToUpload[i].tip + "</h2>";
                    tipOutput += "<p>" + tipsToUpload[i].desc + "</p>";
                    tipOutput += "<br>";
                    tipOutput += '<div class=\"image-' + (i+1) + '\">';
                    tipOutput += "<img src = \"tips-images/" + tipsToUpload[i].image + "\" width=\"400\" height=\"300\">";
                    tipOutput += "</div>";
                    tipOutput += '<div class=\"more_information_container_' + (i+1) +'\"></div>';
                    // tipOutput += "</div>";
                    $(".tip-container-" + (i+1)).append(tipOutput);
                    // $(".total-tip-container").append(tipOutput);
                }
            }

        });
        
        $(".tip-container-1").hide().fadeIn(1000);
        $(".tip-container-2").hide().fadeIn(1000);
        $(".tip-container-3").hide().fadeIn(1000);

        $(".tip-container-1").hover(
            function () {
                const moreInfoContainer = $(".more_information_container_1");
                moreInfoContainer.css("display", "block");
                var output = '';
                var link100 = tipsToUpload[0].link1;
                var link200 = tipsToUpload[0].link2;
                output += '<p id="more_info_1">Need more information? We\'ve got you covered:</p>';
                output += '<a href="' + link100 + '" target="_blank">Discover more details in this first source.</a>';
                output += '<br>';
                output += '<a href="' + link200 + '" target="_blank">Still curious? Find out more in this second source!</a>';
                $(".more_information_container_1").html(output); // Add the content
            },
            function () {
                $(".more_information_container_1").html(''); // Clear the content
            }
        );

        $(".tip-container-2").hover(
            function () {
                const moreInfoContainer = $(".more_information_container_2");
                moreInfoContainer.css("display", "block");
                var output = '';
                var link100 = tipsToUpload[1].link1;
                var link200 = tipsToUpload[1].link2;
                output += '<p id="more_info_2">Need more information? We\'ve got you covered:</p>';
                output += '<a href="' + link100 + '" target="_blank">Discover more details in this first source.</a>';
                output += '<br>';
                output += '<a href="' + link200 + '" target="_blank">Still curious? Find out more in this second source!</a>';
                $(".more_information_container_2").html(output); // Add the content
            },
            function () {
                $(".more_information_container_2").html(''); // Clear the content
            }
        );

        $(".tip-container-3").hover(
            function () {
                const moreInfoContainer = $(".more_information_container_3");
                moreInfoContainer.css("display", "block");
                var output = '';
                var link100 = tipsToUpload[2].link1;
                var link200 = tipsToUpload[2].link2;
                output += '<p id="more_info_3">Need more information? We\'ve got you covered:</p>';
                output += '<a href="' + link100 + '" target="_blank">Discover more details in this first source.</a>';
                output += '<br>';
                output += '<a href="' + link200 + '" target="_blank">Still curious? Find out more in this second source!</a>';
                $(".more_information_container_3").html(output); // Add the content
            },
            function () {
                $(".more_information_container_3").html(''); // Clear the content
            }
        );
    }
    else {
        var output = '';
        output += '<p id="no_tips">You have no tips at the moment. Great job!</p>';
        $(".no-tip-container").append(output);
    }
});