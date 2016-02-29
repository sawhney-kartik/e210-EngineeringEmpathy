(function(window, document, undefined) {

    // pane elements
    var rightPane = document.getElementById('right-pane');
    var leftPane = document.getElementById('left-pane');
    // button and input elements
    var newQuestionBtn = document.querySelector('#interactors > a.btn'); //gets the new question button
    var searchInput = document.getElementById('search'); //this is the search textbox
    var questionIndex; //this keeps track of the index of the active question in the questions array

    var questions = getStoredQuestions(); //this gets the questions from the local storage

    // script elements that correspond to Handlebars templates
    var questionFormTemplate = document.getElementById('question-form-template');
    var questionsTemplate = document.getElementById('questions-template');
    var expandedQuestionTemplate = document.getElementById('expanded-question-template');

    // compiled Handlebars templates
    var templates = {
        renderQuestionFormTemplate: Handlebars.compile(questionFormTemplate.innerHTML),
        renderQuestionsTemplate: Handlebars.compile(questionsTemplate.innerHTML),
        renderExpandedQuestionTemplate: Handlebars.compile(expandedQuestionTemplate.innerHTML)
    };

    /* Returns the questions stored in localStorage. */
    function getStoredQuestions() {
        if (!localStorage.questions) {
            // default to empty array
            localStorage.questions = JSON.stringify([]);
        }

        return JSON.parse(localStorage.questions);
    }

    /* Store the given questions array in localStorage.
     *
     * Arguments:
     * questions -- the questions array to store in localStorage
     */
    function storeQuestions(questions) {
        localStorage.questions = JSON.stringify(questions);
    }

    window.addEventListener('DOMContentLoaded', initializePage()); //gets the initial layout of the page

    /*
    * When a new question is added on the right pane, add it to the local storage and display list of updated questions
    */
    function submitQuestions(event) {
        event.preventDefault();
    var questionForm = document.getElementById('question-form');
    var questionSubject = questionForm.getElementsByTagName('input')[0]; //gets the subject field in the form
    var questionText = questionForm.getElementsByTagName('textarea')[0]; //gets the text field in the form

        var question = { //create the object to be stored in the local storage
            'subject': questionSubject.value, 
            'question': questionText.value,
            'id': Math.random()
        };
        questions.unshift(question); //the more recent question should be displayed first
        storeQuestions(questions); //store in the local storage
        initializePage(); //gets the original layout with left and right panes
    }

    /*
    * Shows the question info and responses for the question clicked. 
    */
    function showExpandedQuestion(event) {
        questionID = this.getAttribute('id'); //gets the ID associated with the question clicked
        for (var i=0;i<questions.length;i++) { //determine the question
            if (questions[i].id == questionID) {
                rightPane.innerHTML = templates.renderExpandedQuestionTemplate(questions[i]); //display the question and the corresponding responses and response form
                questionIndex = i; //will be used later in resolve and submitResponse functions
            }
        }

        var responseForm = document.getElementById('response-form');
        responseForm.addEventListener('submit', submitResponse);
        var resolveElem = document.querySelector('.resolve-container > a.btn'); //gets the resolve button
        resolveElem.addEventListener('click', resolveQuestion);
    }

    /*
    *Adds the response to the localStorage
    */
    function submitResponse(event) {
        event.preventDefault();
        var responseForm = document.getElementById('response-form');
        var respondantName  = responseForm.getElementsByTagName('input')[0];
        var response = responseForm.getElementsByTagName('textarea')[0]; //gets the response text

        var responseObj = { //creates the object which will be stored in the local storage
            'name': respondantName.value,
            'response': response.value
        };

        if(!questions[questionIndex].responses) { //no responses, so create an array to store response objects
            questions[questionIndex].responses = [responseObj];
        }  else{ //just push this object to the array
            questions[questionIndex].responses.push(responseObj);
        }
        storeQuestions(questions); //store the responses with the associated question
        rightPane.innerHTML = templates.renderExpandedQuestionTemplate(questions[questionIndex]); //display the current question
    }

    /*
    * Mark question as resolved and delete it from local storage
    */
    function resolveQuestion() {
        questions.splice(questionIndex, 1); 
        storeQuestions(questions);
        initializePage();
    }

    /*
* Filters the question based on the search input
    */
    function filterQuestions() {
        var searchTerm = searchInput.value;
        if(searchTerm.length>1){ //no filter if the search field is empty
            var filter = questions.filter(function(questionObj) {
                if (questionObj.subject.indexOf(searchTerm) >= 0 || questionObj.question.indexOf(searchTerm) >= 0) { //if the question subject/text contains the search keyword
                    return true;
                }
                else {
                    return false;
                }
            });
            updateLeftPane(filter); //update the left pane to display only the filtered questions
        }
        else {
            initializePage(); //standard layout with all questions
        }
    }

    /*
    *Updates the left pane
    */
    function updateLeftPane(filter) {
        if (filter) {
            leftPane.innerHTML = templates.renderQuestionsTemplate({'questions': filter});
        } else {
            leftPane.innerHTML = templates.renderQuestionsTemplate({'questions': questions});
        }

        var questionInfo = document.querySelectorAll('.question-info');
        for(var i = 0; i < questionInfo.length; i++) {
            questionInfo[i].addEventListener('click', showExpandedQuestion); //event listener to display more information about the question, such as the option to resolve or submit responses
        }
    }

    /*
    * When the page is loaded, display the question form and the left pane with the updated questions
    */
    function initializePage() {
        // display question form 
        rightPane.innerHTML = templates.renderQuestionFormTemplate();
        updateLeftPane();
        var questionForm = document.getElementById('question-form');
        questionForm.addEventListener('submit', submitQuestions); //to submit questions
        newQuestionBtn.addEventListener('click', initializePage); //display the form to add new questions (the original template)
        searchInput.addEventListener('keyup', filterQuestions); //as the user types into the search input field, appropriate questions to be displayed
    }
})(this, this.document);
