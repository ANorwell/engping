<html>
  <head>
    <title>Translate API Example</title>
 <script src="https://www.google.com/jsapi?key=ABQIAAAAvBr3E5gjsEcbIKWhctpmAxRpQYh-aYtvQo0i7nvVd6d9bhl1exQlUHxuWciZUEMKMNoGYoRj8jqX3w" type="text/javascript"></script>

  </head>
  <body>
    <h3>Source</h3>
    <div id="sourceText">Hello world</div>

    <h3>Translation</h3>
    <div id="translation"></div>

    <h3>Diacritization</h3>
    <div id="diacritize"></div>

    <h3>Transliteration</h3>
    <div id="transliteration"></div>
    
    <script type="text/javascript">

    //TODO: diacretize using diacretization api
    //http://en.wikipedia.org/wiki/Romanization_of_Persian#Transliteration

    
    
      function translateText(response) {
          var trText = response.data.translations[0].translatedText;
        document.getElementById("translation").innerHTML += "<br>" + trText;

          var container = document.getElementById("transliteration");
          
          google.language.transliterate([trText], "fa", "en", function(result) {
              if (!result.error) {

                  if (result.transliterations && result.transliterations.length > 0 &&
                      result.transliterations[0].transliteratedWords.length > 0) {
                      container.innerHTML += result.transliterations[0].transliteratedWords[0];
                  }
              }  else {
                  container.innerHTML += "error: " + result.error;
              }
          });
      }

    </script>
    <script type="text/javascript">
      google.load("language", "1");
      google.load("elements", "1", {packages: "transliteration", "nocss" : true});
      function go() {
          var targetLang = 'fa';
          var newScript = document.createElement('script');
          newScript.type = 'text/javascript';
          var sourceText = escape(document.getElementById("sourceText").innerHTML);
          var source = 'https://www.googleapis.com/language/translate/v2?key=AIzaSyBx8ygu04GV6xUZK7rZuRerbyghA6lPVSw&source=en&target=' + targetLang + '&callback=translateText&q=' + sourceText;
          newScript.src = source;
          
          // When we add this script to the head, the request is sent off.
          document.getElementsByTagName('head')[0].appendChild(newScript);
      }

      google.setOnLoadCallback(go);
      
    </script>
  </body>
</html>