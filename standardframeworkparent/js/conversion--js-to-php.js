/**
 * Description:
 * Sets the new background image of the specified element according to the image urls found in the data attributes of the element.
 * Allows for 1 picture break.
 *
 * Expects:
 * width -> integer
 * largestLoaded -> integer
 * image -> DOM element
 * pictureBreak -> integer
 *
 * Returns:
 * NONE
 *
 * Note:
 * These are using the standard crops for any image uploaded to the library.
 */
function pgsfConversionJSToPHP (object) {
  var json = '{'
  for (var property in object) {
    var value = object[property]
    console.log(typeof value)
    if (typeof value === 'string' || typeof value === 'number') {
      json += '"' + property + '":"' + value + '",'
    } else {
      if (!value[0]) { // if it is an associative array
        json += '"' + property + '":' + pgsfConversionJSToPHP(value) + ','
      } else {
        json += '"' + property + '":['
        for (var prop in value) {
          json += '"' + value[prop] + '",'
        }
        json = json.substr(0, json.length - 1) + '],'
      }
    }
  }
  return json.substr(0, json.length - 1) + '}'
  // return json + "}";
}

