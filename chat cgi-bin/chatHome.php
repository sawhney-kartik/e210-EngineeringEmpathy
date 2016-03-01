<?php
$value_to_page = array(
    "Ronald C. Albucher, MD" => 'chat.php',
    "Laurel Zappert Banks, PsyD"  => 'chat1.php',
    "Naomi Brown, PhD" => 'chat2.php',
    "Latoya C. Conner, PhD" => 'chat3.php',
    "Ariana Davidson, LCSW" => 'chat4.php'
);
if(isset($_POST['submit'])){
    if(isset($_POST['options']) && isset($value_to_page[$_POST['options']])){
        header('Location: '.$value_to_page[$_POST['options']]);
        return;
    }
} ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Engineering Empathy Interaction Forums</title>
        <meta charset="utf-8" />

        <!-- CSS -->
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700" />
    </head>

    <body>
        <header>
           <!-- <h1><a href="/">Anxiety Community</a></h1>-->
        </header>

        <div id="wrapper">
            <div id="navigation">
                <div id="interactors">
                    <!-- buttons and search -->
                    <a href="#" class="btn">New Post</a>
                    <input type="text" name="search" id="search"
                        placeholder="Search threads/questions..." />
                </div>

                <div id="left-pane">
                    <!-- add threads/questions to this container -->
                </div>
            </div>

            <div id="right-pane">
                <!-- add threads/question, comments/responses, and comment/response form to this
                     container when a thread/question is selected -->
            </div>
        </div>

        <!-- Handlebars templates -->
        <!-- template to display all threads/questions in #left-pane tag. -->
        <script type="text/x-handlebars-template" id="questions-template">
            {{#if questions}}
                {{#each questions}}
                {{! this.id represents this question&#39;s identifier in
                    localStorage. }}
                <div class="list-question question-info" id="{{ this.id }}">
                    <h3>{{ this.subject }}</h3>
                    <p class="currentResponse">{{ this.question }}</p>
                </div>
                {{/each}}
            {{else}}
                {{! no questions exist; display appropriate message }}
                <div class="list-question">
                    <p>No threads/questions could be found.</p>
                </div>
            {{/if}}
        </script>

        <!-- template to display expanded question (question, response form,
             and responses) in #main-panel tag -->
        <script type="text/x-handlebars-template"
                id="expanded-question-template">
            
            <br>
            <br>
            <h3>Thread/Question</h3>
            <div class="question">
                <h4>{{ subject }}</h4>
                <p class="currentResponse">{{ question }}</p> 
            </div>

            <div class="resolve-container">
             <a href="#" class="btn" id="resolveButton">Resolve</a>
            </div>

            <div class="responses">
                <h3 id="commentsResponses">Comments/Responses</h3>

                {{#if responses}}
                    {{#each responses}}
                    <div class="response">
                        <h4>{{ this.name }}</h4>
                        <p class="currentResponse">{{ this.response }}</p>
                    </div>
                    {{/each}}
                {{else}}
                    {{! no comments or responses found }}
                    <p id="noResponses">No comments or responses submitted yet!</p>
                {{/if}}
            </div>

            <form class="cf" id="response-form">
                <h3>Add Comment/Response</h3>
                <div>
                    <label for="name" id="label">Name</label>
                    <input type="text" name="name" placeholder="John Doe" />
                </div>

                <div>
                    <label for="response" id="responseBox">Comment/Response</label>
                    <textarea rows="5" cols="40" type="text"
                        name="response"></textarea>
                </div>

                <input type="submit" class="btn" value="Submit" />
            </form>
        </script>

        <!-- template to display question form in #main-panel tag -->
        <script type="text/x-handlebars-template" id="question-form-template">
            <form class="cf" id="question-form">
                <h2 id="welcome">Welcome to <span>the Anxiety Community</span>!</h2>
<b id="warning"> All content here is moderated by our team of counselors. Due to the inability to respond to urgent safety needs through an anonymous forum, any content that is a threat to yourself or others is strictly not allowed. We also use auto-filters to filter such content. If you are having thoughts of harming yourself or others, contact 911 or walk into your nearest emergency room.</b>
    <br>
    <br>
    <div> <a href ="http://stanford.edu/~kartiks2/EngineeringEmpathy/counselors.html" class="btn" id="backToTherapists"> Back to Therapists Near You </a></div>

                <div>
                    <input type="text" name="subject" placeholder="Subject" />
                </div>

                <div>
                    <textarea rows="5" cols="40" name="question"
                        placeholder="Thread/Question"></textarea>
                </div>

                <input type="submit" class="btn" value="Submit" /> 
            </form>

            <br>
            <br>

             <div id="chat">
             <h3> Chat directly with a 24/7 counselor </h2>
            <p id="chatText"> Select one from the available counselors below.</p>
                <form method="post">
                   <div id="optionsContainer"><div class="option"> <a href="https://therapists.psychologytoday.com/rms/prof_detail.php?profid=32866&sid=1455748090.4706_7374&city=Palo+Alto&state=CA&spec=334&tr=ResultsName"><img src="img/CarolWilliams.jpg"></a> <div class ="nameInfo"><input type="radio" class="radio" name="options" id="Ronald C. Albucher, MD" value="Ronald C. Albucher, MD"> <label class ="therapistOption"> Carol Williams, PhD </label></div></div>
                    <div class="option"><a href="https://stanfordhealthcare.org/doctors/g/cheryl-gorefelton.html#summary-content"><img id="shortPic" src="img/CherylGore-Felton.png"></a> <div class ="nameInfo"><input type="radio" class="radio" name="options" id="Laurel Zappert Banks, PsyD" value="Laurel Zappert Banks, PsyD"><label class ="therapistOption"> Cheryl Gore-Felton, PhD </label></div></div>
                    <div class="option"><a href="https://therapists.psychologytoday.com/rms/prof_detail.php?profid=65809&sid=1455748090.4706_7374&city=Palo+Alto&state=CA&spec=334&tr=ResultsPhoto"><img src="img/RobertWilliams.jpg"> </a> <div class="nameInfo"><input type="radio" class="radio" name="options" id="Naomi Brown, PhD" value="Naomi Brown, PhD"> <label class ="therapistOption"> Robert Williams, PhD </label></div></div> 
                   <div class="option"> <a href="https://therapists.psychologytoday.com/rms/prof_detail.php?profid=103932&sid=1456803149.1314_25177&city=San+Francisco&county=San+Francisco&state=CA&spec=334&tr=ResultsPhoto"><img src="img/HeatherNelsonBrame.jpg"></a><div class="nameInfo"><input type="radio" class="radio"  name="options" id="Latoya C. Conner, PhD" value="Latoya C. Conner, PhD"><label class ="therapistOption"> Heather Nelson Brame, LCSW</label></div></div>
                   <div class="option"> <a href="https://therapists.psychologytoday.com/rms/prof_detail.php?profid=33244&sid=1456803127.1468_25177&city=Oakland&county=Alameda&state=CA&spec=334&tr=ResultsPhoto"><img src="img/WilliamSpivey.jpg"></a> <div class="nameInfo"><input type="radio" class="radio"  name="options" id="Ariana Davidson, LCSW" value="Ariana Davidson, LCSW"> <label class ="therapistOption">William L. Spivey, PhD </label></div></div></div>
                <input type="submit" class="btn" id="chatButton" name="submit" value="Chat now" /> 
                </form>
        </div>
        </script>

        <!-- JavaScript -->
        <script type="text/javascript" src="js/handlebars.js"></script>
        <script type="text/javascript" src="js/script.js"></script>

    </body>
</html>
