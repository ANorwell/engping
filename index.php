<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>English => Pinglish</title>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8"></meta> 
    <script src="https://www.google.com/jsapi?key=ABQIAAAAvBr3E5gjsEcbIKWhctpmAxRpQYh-aYtvQo0i7nvVd6d9bhl1exQlUHxuWciZUEMKMNoGYoRj8jqX3w" type="text/javascript"></script>
 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
 <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.4.custom.min.js"></script>

 <style type="text/css">
   body {
          font-family:sans-serif;
          margin: 0;
          }
  h3 { margin:0; }
  h4 { margin:0; }  
 </style>
 
 </head>
  <body>
    
    <h3 id="trTitle" style="display:inline">Translate: </h3>
    <a id="langs" title="Current translation direction">English To Transliterated Farsi</a>
    <a id="switch" title="Click to change translation direction">&#8644;</a><br/>
    <textarea id="sourceText" rows="2" cols="50">Hello</textarea>

    <div>
      <button id="translate" onClick="startTranslating()">Translate</button>
    </div>

    <br/>
        
    <div>
      <h3>Translation:</h3>
      <div id="goaltext"><br/></div>
      <a id="infolink" style="font-size:0.6em">[info]</a>
    </div>

    <div id="info" style="float:left;border-style:solid;border-width:1px;font-size:small;padding:1em;">
      <h4>Farsi</h4>
      <div id="farsi">n/a</div>
      <h4>Diacritization</h4>
      <div id="diacritize">n/a</div>
    </div>


    <script type="text/javascript">
      var trMap = {
          '%u060C' : ',',
          '%u061F' : '?',

          '%u0626' : 'y',
          '%u0627' : 'a',
          '%u0628' : 'b',
          '%u0629' : '?',
          '%u062A' : 't',
          '%u062B' : 's',          
          '%u062C' : 'j',
          '%u062D' : 'h',
          '%u062E' : 'kh',
          '%u062F' : 'd',
          '%u0630' : 'z',
          '%u0631' : 'r',
          '%u0632' : 'z',
          '%u0633' : 's',
          '%u0634' : 'sh',
          '%u0635' : 's',
          '%u0636' : 'z',
          '%u0637' : 't',
          '%u0638' : 'z',
          '%u0639' : 'a',
          '%u063A' : 'gh',
          '%u063B' : '?',
          '%u063C' : '?',
          '%u063D' : '?',
          '%u063E' : '?',
          '%u063F' : '?',
          '%u0640' : '?',
          '%u0641' : 'f',
          '%u0642' : 'gh',
          '%u0643' : '?',
          '%u0644' : 'l',
          '%u0645' : 'm',
          '%u0646' : 'n',
          '%u0647' : 'h',
          '%u0648' : 'v',
          '%u0649' : 'y',
          '%u064A' : 'y',

          //the vowel marks
          '%u064E' : 'a',
          '%u064F' : 'o',
          '%u0650' : "e",
          '%u0651' : '',  //the w thing means nothing
          '%u0652' : "'",
          '%u06CC' : 'i',

          '%u067E' : 'p',
          '%u0686' : 'ch',
          '%u0698' : 'zh',              
          '%u06A9' : 'k',
          '%u06AF' : 'g',
      };

      var srcLang = 'en';
      var dstLang = 'fa';
      var isInfoShown = 0;
      var trText;
      
      console.log = function() {}; //don't show debug statements


      var apiKey = 'AIzaSyBx8ygu04GV6xUZK7rZuRerbyghA6lPVSw';
      google.load("language", "1");
      google.load("elements", "1", {
          packages: "transliteration"
      });

      //set up the page interface
      $("#info").hide();
      $("#translate").button();
      $("#switch").click(switchLangs);
      $("#infolink").click(function() {
          if (isInfoShown) {
              $("#info").hide();
              isInfoShown = 0;
          } else {
              $("#info").show();
              isInfoShown = 1;
          }
      });

      function switchLangs() {
          if (srcLang == 'en') {
              srcLang = 'fa';
              dstLang = 'en';
              $("#langs").html("Transliterated Farsi to English");
          } else {
              srcLang = 'en';
              dstLang = 'fa';
              $("#langs").html("English to Transliterated Farsi");
          }
          $("#info").hide();
          $("#farsi").html("n/a");
          $("#diacritize").html("n/a");
          
      };

      /////////////////////
      //Functions for translating from Pinglish to English
      ///////////////////////
      
      //this acts on diacritized persian text!
      function translitPrToPing(str) {
          var tr = '';
          for (c in str) {
              console.log(str[c] + ' : ' + escape(str[c]));
              var utf8code = escape(str[c]);
              if (utf8code in trMap) {
                  tr += trMap[utf8code];
              } else {
                  tr += str[c];
              }
          }
          return tr;
      }

      //The callback to handle the response object with diacritized text.
      function callbackDiacrit(response) {
          console.log(escape(trText));
          console.log(escape(response.data.diacritized_text));
          $("#diacritize").html(response.data.diacritized_text);
          $("#goaltext").html(translitPrToPing(response.data.diacritized_text));
      }

      //The callback to handle the response object with tranlated persian text.
      function callbackPrToPing(response) {
          //TODO: diacretize using diacretization api
          //http://en.wikipedia.org/wiki/Romanization_of_Persian#Transliteration

          trText = response.data.translations[0].translatedText;
          $("#farsi").html(trText);

          var diaURL = 'https://www.googleapis.com/language/diacritize/v1?key=' + apiKey +
              '&lang=ar&last_letter=false&prettyprint=false&message=' + trText +
              '&callback=callbackDiacrit';

          $.get(diaURL, function() {}, 'jsonp' );
      }

      //translate src text from srcLang to dstLang, and then run the callback
      function tr(src, callbackName) {
          var trURL = 'https://www.googleapis.com/language/translate/v2?key=' + apiKey +
              '&source=' + srcLang + '&target=' + dstLang + '&callback=' + callbackName +
              '&q=' + src;
          $.get(trURL, function() {}, 'jsonp'); 
      }

      /////////////////////
      //Functions for translating from Pinglish to English
      ///////////////////////
      
      function translitPingToPr(str) {
          //note that here the src language for transliteration is en, and dst is fa
          //the 'opposite' of our larger goal of translating pinglish to english.
          //so srcLang and dstLang are reversed
          google.language.transliterate(
              str.split('%20'), dstLang, srcLang,
              function(result) {
                  var textArray = result.transliterations.map(
                      function(x) {
                          return x.transliteratedWords[0];
                      });
                  var text = textArray.join(" ");
                  $("#farsi").html(text);
                  $("#diacritize").html("n/a");
                  tr(text, 'callbackPrToEng');   
                                        });
      }

      function callbackPrToEng(response) {
          trText = response.data.translations[0].translatedText;
          $("#goaltext").html(trText);
      }

      //////////////////////////////////
      //the main function for translating the text
      //////////////////////////////////
      function startTranslating() {
          var sourceText = escape($("#sourceText").val());

          if (srcLang == 'en') {
              tr(sourceText, 'callbackPrToPing');
          } else {
              translitPingToPr(sourceText);
          }
      }

      
    </script>
  </body>
</html>