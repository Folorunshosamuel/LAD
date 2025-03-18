/**
 * Mouse move parallax effect
 * @requires https://github.com/wagerfield/parallax
 */

const parallaxElements = document.querySelectorAll('.parallax')

for (let i = 0; i < parallaxElements.length; i++) {
  /* eslint-disable no-unused-vars, no-undef */
  const parallaxInstance = new Parallax(parallaxElements[i])
  /* eslint-enable no-unused-vars, no-undef */
}
