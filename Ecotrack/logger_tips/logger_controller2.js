document.addEventListener('DOMContentLoaded', function () {
    const nextButton = document.getElementById('nextButton');
    const prevButton = document.getElementById('prevButton');

    $('.goQuestions').click(function(){
        const getStart = document.querySelector('.total-question-container');
        const getWelcome = document.querySelector('.welcome');
        
        getStart.style.display = 'block'
        getWelcome.style.display = 'none';
    });

    if (nextButton && prevButton) {
        nextButton.addEventListener('click', function () {
            const activeQuestion = document.querySelector('.active-question-container');
            if(activeQuestion){
                activeQuestion.classList.remove('active-question-container');
                activeQuestion.classList.add('disabled-question-container');
                const nextQuestion = activeQuestion.nextElementSibling;
                if (nextQuestion) {
                    nextQuestion.classList.remove('disabled-question-container');
                    nextQuestion.classList.add('active-question-container');
                    const navQuestionCounter = document.querySelector('.question-counter');
                    const nextQuestionCount =  parseInt(nextQuestion.getAttribute('data-question'),10);

                    if(navQuestionCounter && nextQuestionCount){
                        var newQuestionCount = nextQuestionCount + 1;
                        // If the next question is the last one, remove the next button
                        if(newQuestionCount == 5){
                            nextButton.style.display = 'none';
                            viewFinish();
                        }
                        // If the next question is the second question, the previous button should be added back
                        if(newQuestionCount == 2){
                            prevButton.style.display = 'block';
                            prevButton.style.marginLeft = '0';
                        }
                        navQuestionCounter.textContent = 'Question ' + newQuestionCount + ' of 5.';
                    }
                }  
            }
        });

        prevButton.addEventListener('click', function(){
            const activeQuestion = document.querySelector('.active-question-container');
            if(activeQuestion){
                activeQuestion.classList.remove('active-question-container');
                activeQuestion.classList.add('disabled-question-container');
                const prevQuestion = activeQuestion.previousElementSibling;
                if (prevQuestion) {
                    prevQuestion.classList.remove('disabled-question-container');
                    prevQuestion.classList.add('active-question-container');
                    const navQuestionCounter = document.querySelector('.question-counter');
                    const previousQuestionCount =  parseInt(prevQuestion.getAttribute('data-question'),10);
                    if(navQuestionCounter && !isNaN(previousQuestionCount)){
                        var newQuestionCount = previousQuestionCount + 1;
                        // If the previous question is the first one, remove the previous button
                        if(newQuestionCount == 1){
                            prevButton.style.display = 'none';
                        }
                        // If the previous question is the fourth question, the next button should be added back
                        if(newQuestionCount == 4){
                            // Ensure that the finish button is removed before moving on
                            const finishChecker = document.querySelector('.finish');
                            if(finishChecker){
                                finishChecker.style.display = 'none';
                            }
                            nextButton.style.display = '';
                            nextButton.style.marginRight = '0';
                        }
                        navQuestionCounter.textContent = 'Question ' + newQuestionCount + ' of 5.';
                    }
                }
            }
        });
    }

    const finButton = document.querySelector('.finish');
    console.log("Clicked!");
    if(finButton){
        finButton.addEventListener('click', function(){
            clickFinish();
        });
    }

    $('.submit-button').click(function(){ 
        console.log("Sending data:", {
            responseMap: responseMap,
            questionsArr: questionsArr,
            changeValues: changeValues
        });

        // Initializing all answers and all subjects
        var answerArray = [];
        for(let [key,value] of responseMap){
            answerArray.push(value);
        }

        var answerValueArray = []
        for(let [key,value] of changeValues){
            answerValueArray.push(value);
        }

        var subjectsArray = [];
        for (var i = 0; i < questionsArr.length; i++) {
            subjectsArray.push(questionsArr[i].subject);
        }
                
        // Serialize the array into a JSON string
        var postData = {
            subjects: JSON.stringify(subjectsArray),
            answers: JSON.stringify(answerArray),
            answersValue: JSON.stringify(answerValueArray)
        };
        
        $.ajax({
            type: "POST",
            url: "updateScores.php",
            dataType: "json",
            data: postData,
            success: function(responseData, status) {
                console.log("Response from server:", responseData);
            },
            error: function(msg) {
                console.log("Error:", msg.status + " " + msg.statusText);
            }
        });    

        // Make everything dissapear except for the results text
        const getBackSubmit = document.querySelector('.back-submit');
        const getAnswersContainer = document.querySelector('.review-answers');

        getAnswersContainer.style.display = 'none';
        getBackSubmit.style.display = 'none';
        
        // Now get the congratulations message and display it
        const getThanks = document.querySelector('.thanks');
        getThanks.style.display = 'flex';
        const getViewSustain = document.querySelector('.view-sustain-tips');
        getViewSustain.style.display = "block";
    });

    $('.back-button').click(function(){
        const getFinish = document.querySelector('.finish');
        const getLastQuestion = document.querySelector('.last-question-container');
        const getTotalContainer = document.querySelector('.total-question-container');
        const getAnswersContainer = document.querySelector('.review-answers');
        const getBackSubmit = document.querySelector('.back-submit');
    
        getAnswersContainer.style.display = 'none';
        getBackSubmit.style.display = 'none';
        getTotalContainer.style.display = 'block';
        
        getLastQuestion.classList.remove('last-question-container');
        getLastQuestion.classList.add('active-question-container');
    
        getFinish.style.display = 'block';
    });

    if (typeof selectOption === 'function') {
        // Only redefine if the inline definition exists
        selectOption = function(button, questionIndex, responseMap) {
            var buttonValue = button.textContent;
            var optionData = button.dataset.optiondata;

            questionIndex++;
            var mapKey = questionIndex.toString();

            responseMap.set(mapKey, buttonValue);
            changeValues.set(mapKey, optionData);
            console.log(changeValues);

            console.log(responseMap);

            if(questionIndex < 5){
                document.getElementById('nextButton').click();
            }
            else{
                viewFinish();
            }
        };
    }
});

function viewFinish(){
    const finishButton = document.querySelector('.finish');
    for(let [key,value] of responseMap){
        if(value == "NULL"){
            // User still has to complete more questions
            return;
        }
    }
    // If reached here, user has answered all questions and finish is displayed
    finishButton.style.display = 'block';
}

function clickFinish(){
    const finishButtonCheck = document.querySelector('.finish');

    // Disable the viewing for the current active question along with the finish button

    finishButtonCheck.style.display = 'none';

    var activeQues = document.querySelector('.active-question-container');
    activeQues.classList.remove('active-question-container');
    activeQues.classList.add('last-question-container');
    var totalContainer = document.querySelector('.total-question-container');
    totalContainer.style.display = 'none';

    // Build the review answers
    var output = '<div class="review-answers">';
    output += "<h4>Please review your answers before you submit.</h4>";
    output += '<div class="down-arrow">↓</div>';
    let arrIndex = 0;

    for(let [key,value] of responseMap){
        const questString = questionsArr[arrIndex].q;
        output += "<p>" + questString + "   " + "<em>" + value + "</em></p>";
        arrIndex++;
    }
    output += "</div>";

    const checkAnswersContainer = document.querySelector('.check_answers');
    checkAnswersContainer.innerHTML = output;
    const checkBackSubmit = document.querySelector('.back-submit');
    checkBackSubmit.style.display = 'flex';
}