/**
 * Range slider
 * @requires https://github.com/leongersen/noUiSlider
 */

let rangeSliderWidget = document.querySelectorAll('.range-slider')

for (let i = 0; i < rangeSliderWidget.length; i++) {
  let rangeSlider = rangeSliderWidget[i].querySelector('.range-slider-ui'),
    valueMinInput = rangeSliderWidget[i].querySelector(
      '.range-slider-value-min'
    ),
    valueMaxInput = rangeSliderWidget[i].querySelector(
      '.range-slider-value-max'
    )

  let options = {
    dataStartMin: parseInt(rangeSliderWidget[i].dataset.startMin, 10),
    dataStartMax: parseInt(rangeSliderWidget[i].dataset.startMax, 10),
    dataMin: parseInt(rangeSliderWidget[i].dataset.min, 10),
    dataMax: parseInt(rangeSliderWidget[i].dataset.max, 10),
    dataStep: parseInt(rangeSliderWidget[i].dataset.step, 10),
    dataPips: rangeSliderWidget[i].dataset.pips,
    dataTooltips: rangeSliderWidget[i].dataset.tooltips
      ? rangeSliderWidget[i].dataset.tooltips === 'true'
      : true,
    dataTooltipPrefix: rangeSliderWidget[i].dataset.tooltipPrefix || '',
    dataTooltipSuffix: rangeSliderWidget[i].dataset.tooltipSuffix || '',
  }

  let start = options.dataStartMax
    ? [options.dataStartMin, options.dataStartMax]
    : [options.dataStartMin],
    connect = options.dataStartMax ? true : 'lower'

  /* eslint-disable no-undef */
  noUiSlider.create(rangeSlider, {
    start: start,
    connect: connect,
    step: options.dataStep,
    pips: options.dataPips ? { mode: 'count', values: 5 } : false,
    tooltips: options.dataTooltips,
    range: {
      min: options.dataMin,
      max: options.dataMax,
    },
    format: {
      to: function (value) {
        return (
          options.dataTooltipPrefix +
          parseInt(value, 10) +
          options.dataTooltipSuffix
        )
      },
      from: function (value) {
        return Number(value)
      },
    },
  })
  /* eslint-enable no-undef */

  rangeSlider.noUiSlider.on('update', (values, handle) => {
    let value = values[handle]
    value = value.replace(/\D/g, '')
    if (handle) {
      if (valueMaxInput) {
        valueMaxInput.value = Math.round(value)
      }
    } else {
      if (valueMinInput) {
        valueMinInput.value = Math.round(value)
      }
    }
  })

  if (valueMinInput) {
    valueMinInput.addEventListener('change', function () {
      rangeSlider.noUiSlider.set([this.value, null])
    })
  }

  if (valueMaxInput) {
    valueMaxInput.addEventListener('change', function () {
      rangeSlider.noUiSlider.set([null, this.value])
    })
  }
}
