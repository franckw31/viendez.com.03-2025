<style>
body {
  width: 100%;
  height: screen;
}

.wrapper {
  margin: 50px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
}
</style>
<script>
    const textInputField = document.querySelector("#text-input")
const form = document.querySelector("#form")
const utterThis = new SpeechSynthesisUtterance()
const synth = window.speechSynthesis
let ourText = ""

const checkBrowserCompatibility = () => {
  "speechSynthesis" in window
    ? console.log("Web Speech API supported!")
    : console.log("Web Speech API not supported :-(")
}

checkBrowserCompatibility()

form.onsubmit = (event) => {
  event.preventDefault()
  ourText = textInputField.value
  utterThis.text = ourText
  synth.speak(utterThis)
  textInputField.value = ""
}
</script>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>JavaScript Text to Speech App</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <div class="wrapper">
      <h1>JavaScript Text-to-Speech App</h1>
      <form id="form">
        <input type="text" id="text-input" placeholder="Enter Text" />
        <button type="submit" id="submit-button">Submit</button>
      </form>
    </div>
    <script src="sc.js"></script>
  </body>
</html>
