var questionsArr = [];

$(document).ready(function () {
    console.log("Document ready");  // Check if code is running at all

    class Question {
        constructor(q, choices, links, subject){
            this.q = q;
            this.choices = choices;
            this.links = links;
            this.subject = subject;
        }
    }

    $.ajax({
        url: "getUserInfo.php",
        method: "GET",
        dataType: "json",
    })
    .then(function(responseData){
        console.log("First AJAX response:", responseData);  // Check first AJAX response
        const firstName = responseData.first_name;
        const getWelcomeMsg = document.querySelector('.WelcomeMsg');
        getWelcomeMsg.append(firstName + " Activity Logger");

        return $.ajax({
            url: "questionBank.js",
            method: "GET",
            dataType: "json",
        });
    })
    .then(function(questionData){
        console.log("Second AJAX response:", questionData);  // Check second AJAX response
        const totalCategories = ["transportation", "energy", "waste", "purchasing", "diet"];
        const questionBank = questionData.questionBank;
        // Selects five completely random questions from logger_questions.js
        while(totalCategories.length > 0){
            const randomIndex = Math.floor(Math.random() * totalCategories.length);
            var categoryName = totalCategories[randomIndex];
            const randomCategory = totalCategories.splice(randomIndex, 1)[0];

            for(var i = 0; i < questionBank.length; i++){
                if(questionBank[i].category == categoryName){
                    var questionsList = questionBank[i].questions;
                    var randomIndex2 = Math.floor(Math.random() * questionsList.length);
                    var selectedQuestion = questionsList[randomIndex2];
                    // Constructs a new Question instance based on selected random question
                    // console.log(selectedQuestion.image);
                    const q100 = new Question(
                        selectedQuestion.question,
                        selectedQuestion.choices,
                        selectedQuestion.links,
                        selectedQuestion.subject
                    );
                    console.log(JSON.stringify(selectedQuestion, null, 2));                         
                    questionsArr.push(q100);
                    break;
                }
            }
        }
        console.log("Questions array built:", questionsArr);  // Check array before returning
        return questionsArr;
    })
    .then(function(questionsArr) {
        // Creates HTML for each question, with the first one being unique
        for (var i = 0; i < questionsArr.length; i++) {
            if(i==0){
                var output = "<div class=\"active-question-container\" data-question=\"" + i + "\">"; 
            }
            else{
                var output = "<div class=\"disabled-question-container\" data-question=\"" + i + "\">"; 
            }
            output += "<div class=\"question\">" + questionsArr[i].q + "</div>";
            output += "<div class=\"img\">" + "<img src=\"images/" + questionsArr[i].links + ".png\" width=\"375\" height=\"300\">" + "</div>";
            output += "<div class=\"options-container\">";

            for (var j = 0; j < questionsArr[i].choices.length; j++) {
                output += "<button class=\"option-circle\" data-optiondata=\"" + j + 
                          "\" onclick=\"selectOption(this, " + i + ", responseMap)\">" 
                          + questionsArr[i].choices[j] + "</button>";
            }
        
            output += "</div>";
            output += "</div>";

            $('.total-question-container').append(output);
        }
    })
    .catch(function(error) {
        console.error("Error occurred:", error);
    });
});