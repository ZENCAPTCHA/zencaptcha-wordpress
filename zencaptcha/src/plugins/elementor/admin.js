class zencaptchaAdminElementorPro extends elementorModules.editor.utils.Module {

	onInit() {
elementor.hooks.addFilter(
		'elementor_pro/forms/content_template/field/zencaptcha',
		function ( inputField, item, i ) {              
			const fieldId = `form_field_${i}`;
			return `<style>
			.zenc-spinner {width: 28px !important;height: 28px !important;display: inline-block !important;position: absolute !important;top: 0 !important;left: 0 !important;border: 3px solid #0000001a !important;border-left-color: #003399 !important;border-radius: 50% !important;animation: zenc-spin 1.3s linear infinite !important;box-sizing: border-box !important;}@keyframes zenc-spin {0% {transform: rotate(0deg) !important;}100% {transform: rotate(360deg) !important;}}.zenc-circular-progress-bar, .zenc-checkmark {position: absolute !important;width: 35px !important;height: 35px !important;top: 0 !important;left: 0 !important;}.zenc-circle {transition: stroke-dasharray 0.5s linear !important;}.zenc-left {position: relative !important;display: table !important;top: 0 !important;height: 100% !important;}.zenc-label {color: rgb(85, 85, 85) !important;font-size: 14px !important;}.zenc-left-container {display: table-cell !important;vertical-align: middle !important;}.zenc-label-container {position: relative !important;display: inline-block !important;height: 100% !important;width: 170px !important;}.zenc-label-td {position: relative !important;display: table !important;top: 0 !important;height: 100% !important;}.zenc-label-tc {display: table-cell !important;vertical-align: middle !important;}.zenc-left-content {position: relative !important;width: 30px !important;height: 30px !important;margin: 0 15px !important;cursor: pointer !important;}.zenc-checkbox {position: absolute !important;width: 28px !important;height: 28px !important;border-width: 1px !important;border-style: solid !important;border-color: rgb(145, 145, 145) !important;border-radius: 4px !important;background-color: #ffffff !important;top: 0 !important;left: 0 !important;}.zenc-captcha {position: relative !important;box-sizing: content-box !important;width: 300px !important;height: 74px !important;padding: 0 !important;margin: 0 !important;margin-bottom: 10px !important;border-width: 1px !important;border-style: solid !important;border-radius: 4px !important;border-color: rgb(224, 224, 224) !important;background-color: rgb(250, 250, 250) !important;display: block !important;font-family: -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, 'Helvetica Neue', Arial, sans-serif !important;}.zenc-captcha b {font-weight: 700 !important;}.zenc-container {display: flex !important;align-items: center !important;height: 74px !important;}.zenc-success .zenc-icon {animation: 1s ease-in both zenc-fade-in !important;}.zenc-content {white-space: nowrap !important;display: flex !important;flex-direction: column !important;margin: 4px 6px 0 10px !important;overflow-x: auto !important;flex-grow: 1 !important;}.zenc-captcha-solution {display: none !important;}.zenc-err-url {text-decoration: underline !important;font-size: 0.9em !important;}@keyframes zenc-fade-in {from {opacity: 0 !important;}to {opacity: 1 !important;}}.zenc-css-spinner {width: 28px !important;height: 28px !important;display: inline-block !important;position: absolute !important;top: 0 !important;left: 0 !important;border: 4px solid #0000001a !important;border-left-color: blue !important;border-radius: 50% !important;animation: zenc-css-spin 1s linear infinite !important;box-sizing: border-box !important;}@keyframes zenc-css-spin {0% {transform: rotate(0deg) !important;}100% {transform: rotate(360deg) !important;}}       
			</style>
			<div class="zenc-captcha" id="${fieldId}" style="
				position: relative;
				width: 100%;
				text-align: center;
				border: 1px solid #f4f4f4;
				padding-bottom: 20px;
				padding-top: 20px;
				background-color: #fff;">
			<div class="zenc-container">
<div class="zenc-left-wrapper">
    <div class="zenc-left-container">
      <div class="zenc-left-content">
        
	  <div id="zenc-checkbox-id" aria-haspopup="true" aria-checked="false" role="checkbox" tabindex="0" aria-live="assertive" aria-labelledby="a11y-label" class="zenc-checkbox" aria-describedby="status"></div>

      </div>
    </div>
  </div>
<div class="zenc-label-container">
  <label-td class="zenc-label-td">
    <label-tc class="zenc-label-tc">
      <div class="zenc-label">ZENCAPTCHA DEMO</div>
    </label-tc>
  </label-td>
</div>
	
	
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="60" viewBox="0 0 375 375" height="60" version="1.0">
  <defs>
    <clipPath id="a">
      <path d="M55.82 37.445h135.461v135.461H55.821Zm0 0"></path>
    </clipPath>
    <clipPath id="b">
      <path d="M123.55 37.445c-37.405 0-67.73 30.325-67.73 67.73 0 37.407 30.325 67.731 67.73 67.731 37.407 0 67.731-30.324 67.731-67.73 0-37.406-30.324-67.73-67.73-67.73"></path>
    </clipPath>
    <clipPath id="c">
      <path d="M37.5 0h171.75v210H37.5Zm0 0"></path>
    </clipPath>
    <clipPath id="d">
      <path d="M218 33.066h30V61h-30Zm0 0"></path>
    </clipPath>
    <clipPath id="e">
      <path d="M218 205h30v28.316h-30Zm0 0"></path>
    </clipPath>
    <clipPath id="f">
      <path d="M304 119h29.54v28H304Zm0 0"></path>
    </clipPath>
    <clipPath id="g">
      <path d="M170.977 75H173v29h-2.023Zm0 0"></path>
    </clipPath>
    <clipPath id="h">
      <path d="M170.977 162H173v29h-2.023Zm0 0"></path>
    </clipPath>
    <clipPath id="i">
      <path d="M166.172 33.066h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
    <clipPath id="j">
      <path d="M209.605 25.254h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
    <clipPath id="k">
      <path d="M253.035 37.445h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
    <clipPath id="l">
      <path d="M284.121 66.555h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
    <clipPath id="m">
      <path d="M296.469 112.629h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
    <clipPath id="n">
      <path d="M284.121 158.488h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
    <clipPath id="o">
      <path d="M253.035 187.824h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
    <clipPath id="p">
      <path d="M209.605 195.48h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
    <clipPath id="q">
      <path d="M166.172 186.707h43.5v41.25h-43.5Zm0 0"></path>
    </clipPath>
  </defs>
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <path fill="#ffcd00" d="M55.82 37.445h135.461v135.461H55.821Zm0 0"></path>
    </g>
  </g>
  <g clip-path="url(#c)">
    <path fill="#039" d="M123.375 0 37.504 38.184v57.27c0 53.026 36.59 102.472 85.871 114.546 49.281-12.074 85.871-61.52 85.871-114.547v-57.27Zm-19.082 152.727-38.168-38.18 13.504-13.508 24.664 24.676 62.828-62.86 13.504 13.508Zm0 0"></path>
  </g>
  <g clip-path="url(#d)">
    <path fill="#ffcd00" d="m232.547 33.059 3.41 10.558 11.106-.02-8.997 6.505 3.442 10.543-8.961-6.54-8.98 6.54 3.456-10.543-8.992-6.504 11.102.02 3.414-10.56"></path>
  </g>
  <g clip-path="url(#e)">
    <path fill="#ffcd00" d="m232.547 205.738 3.41 10.555 11.106-.016-8.997 6.5 3.442 10.54-8.961-6.532-8.98 6.531 3.456-10.539-8.992-6.5 11.102.016 3.414-10.555"></path>
  </g>
  <path fill="#ffcd00" d="m276 44.242 3.414 10.559 11.117-.024-9.008 6.504 3.457 10.543L276 65.29l-8.96 6.535 3.44-10.543-8.996-6.504 11.106.024 3.41-10.56m31.395 31.735 3.41 10.554 11.12-.02-9.007 6.505 3.453 10.543-8.976-6.54-8.965 6.54 3.457-10.543-9.008-6.504 11.105.02 3.41-10.555"></path>
  <g clip-path="url(#f)">
    <path fill="#ffcd00" d="m318.992 119.277 3.41 10.559 11.106-.02-8.996 6.5 3.457 10.551-8.977-6.547-8.98 6.547 3.457-10.55-8.992-6.5 11.101.019 3.414-10.559"></path>
  </g>
  <path fill="#ffcd00" d="m307.32 162.426 3.41 10.566 11.106-.027-9.008 6.515 3.457 10.536-8.965-6.532-8.98 6.532 3.457-10.536-9.008-6.515 11.121.027 3.41-10.566m-31.695 31.73 3.414 10.567 11.117-.016-9.008 6.504 3.457 10.535-8.98-6.531-8.96 6.531 3.456-10.535-9.008-6.504 11.102.016 3.41-10.567M189.332 44.242l-3.414 10.555-11.117-.02 9.008 6.504-3.457 10.543 8.98-6.539 8.961 6.54-3.441-10.544 8.996-6.504-11.106.02-3.41-10.555"></path>
  <g clip-path="url(#g)">
    <path fill="#ffcd00" d="m157.93 75.973-3.41 10.558-11.11-.023 8.996 6.508-3.449 10.543 8.973-6.54 8.972 6.54-3.453-10.543 9.004-6.508-11.113.023-3.41-10.558"></path>
  </g>
  <g clip-path="url(#h)">
    <path fill="#ffcd00" d="m158.02 162.426-3.41 10.566-11.11-.027 9 6.515-3.453 10.536 8.973-6.532 8.968 6.532-3.445-10.536 9-6.515-11.11.027-3.413-10.566"></path>
  </g>
  <path fill="#ffcd00" d="m189.707 194.156-3.414 10.567-11.117-.016 9.008 6.504-3.457 10.535 8.98-6.531 8.961 6.531-3.457-10.535 9.008-6.504-11.117.016-3.395-10.567"></path>
  <g clip-path="url(#i)">
    <path fill="#ffcd00" d="m187.922 33.066 5.601 12.559a3.014 3.014 0 0 0 2.442 1.766l13.707 1.433-10.246 9.192a3.003 3.003 0 0 0-.934 2.859l2.871 13.445-11.933-6.879a3.035 3.035 0 0 0-1.508-.402c-.527 0-1.047.14-1.508.402l-11.934 6.88 2.872-13.446a3.003 3.003 0 0 0-.93-2.86l-10.246-9.19 13.707-1.434a2.996 2.996 0 0 0 1.457-.559c.43-.309.765-.727.98-1.207Zm0 0" fill-rule="evenodd"></path>
  </g>
  <g clip-path="url(#j)">
    <path fill="#ffcd00" d="m231.355 25.254 5.602 12.558c.215.485.55.903.98 1.211.43.309.93.504 1.458.559l13.707 1.43-10.247 9.195c-.394.352-.687.8-.847 1.305a2.96 2.96 0 0 0-.082 1.554l2.867 13.442-11.93-6.875a3 3 0 0 0-3.016 0l-11.933 6.875 2.871-13.442a3.001 3.001 0 0 0-.933-2.859l-10.247-9.195 13.708-1.43a3.02 3.02 0 0 0 2.44-1.77Zm0 0" fill-rule="evenodd"></path>
  </g>
  <g clip-path="url(#k)">
    <path fill="#ffcd00" d="m274.785 37.445 5.602 12.559a3.014 3.014 0 0 0 2.441 1.766l13.707 1.433-10.246 9.192a3.003 3.003 0 0 0-.934 2.859l2.872 13.441-11.934-6.875a2.995 2.995 0 0 0-1.508-.402c-.527 0-1.05.137-1.508.402l-11.933 6.875 2.87-13.441a3.003 3.003 0 0 0-.929-2.86l-10.246-9.19 13.707-1.434a3.014 3.014 0 0 0 2.438-1.766Zm0 0" fill-rule="evenodd"></path>
  </g>
  <g clip-path="url(#l)">
    <path fill="#ffcd00" d="m305.871 66.555 5.602 12.558c.214.48.554.899.98 1.211.43.309.934.5 1.457.555l13.707 1.433-10.242 9.192a3.003 3.003 0 0 0-.934 2.86l2.871 13.445-11.933-6.88a3.035 3.035 0 0 0-3.016 0l-11.933 6.88 2.87-13.446a3.003 3.003 0 0 0-.933-2.86l-10.242-9.19 13.707-1.434a3.044 3.044 0 0 0 1.457-.555c.426-.312.766-.73.98-1.21Zm0 0" fill-rule="evenodd"></path>
  </g>
  <g clip-path="url(#m)">
    <path fill="#ffcd00" d="m318.219 112.629 5.597 12.558a3.023 3.023 0 0 0 2.442 1.766l13.707 1.434-10.246 9.191a3.003 3.003 0 0 0-.852 1.305 3.003 3.003 0 0 0-.078 1.554l2.867 13.442-11.93-6.875a3.017 3.017 0 0 0-1.507-.402c-.531 0-1.051.136-1.508.402l-11.934 6.875 2.871-13.441a3.003 3.003 0 0 0-.933-2.86l-10.246-9.191 13.707-1.434a2.981 2.981 0 0 0 1.457-.558c.43-.31.77-.727.984-1.207Zm0 0" fill-rule="evenodd"></path>
  </g>
  <g clip-path="url(#n)">
    <path fill="#ffcd00" d="m305.871 158.488 5.602 12.559c.214.484.554.902.98 1.21.43.31.934.505 1.457.56l13.707 1.43-10.242 9.194a3.001 3.001 0 0 0-.934 2.86l2.871 13.441-11.933-6.875a2.995 2.995 0 0 0-1.508-.402c-.531 0-1.05.137-1.508.402l-11.933 6.875 2.87-13.441a3.001 3.001 0 0 0-.933-2.86l-10.242-9.195 13.707-1.43a3.05 3.05 0 0 0 1.457-.558c.426-.309.766-.727.98-1.211Zm0 0" fill-rule="evenodd"></path>
  </g>
  <g clip-path="url(#o)">
    <path fill="#ffcd00" d="m274.785 187.824 5.602 12.555a2.999 2.999 0 0 0 2.441 1.77l13.707 1.43-10.246 9.194a3.001 3.001 0 0 0-.934 2.86l2.872 13.441-11.934-6.875a2.995 2.995 0 0 0-1.508-.402c-.527 0-1.05.137-1.508.402l-11.933 6.875 2.87-13.441a3.001 3.001 0 0 0-.929-2.86l-10.246-9.195 13.707-1.43a2.996 2.996 0 0 0 1.457-.558c.426-.309.766-.727.98-1.211Zm0 0" fill-rule="evenodd"></path>
  </g>
  <g clip-path="url(#p)">
    <path fill="#ffcd00" d="m231.355 195.48 5.602 12.56c.215.48.55.898.98 1.206.43.313.93.504 1.458.559l13.707 1.433-10.247 9.192a2.96 2.96 0 0 0-.847 1.304 2.963 2.963 0 0 0-.082 1.555l2.867 13.441-11.93-6.875a3.057 3.057 0 0 0-1.508-.402c-.53 0-1.05.14-1.507.402l-11.934 6.875 2.871-13.44a3.003 3.003 0 0 0-.933-2.86l-10.247-9.192 13.708-1.433a2.981 2.981 0 0 0 1.457-.559c.43-.309.77-.726.984-1.207Zm0 0" fill-rule="evenodd"></path>
  </g>
  <g clip-path="url(#q)">
    <path fill="#ffcd00" d="m187.922 186.707 5.601 12.559a3.014 3.014 0 0 0 2.442 1.765l13.707 1.434-10.246 9.191a3.003 3.003 0 0 0-.934 2.86l2.871 13.441-11.933-6.875a2.995 2.995 0 0 0-1.508-.402c-.527 0-1.047.136-1.508.402l-11.934 6.875 2.872-13.441a3.003 3.003 0 0 0-.93-2.86l-10.246-9.191 13.707-1.434a2.996 2.996 0 0 0 1.457-.558c.43-.309.765-.727.98-1.207Zm0 0" fill-rule="evenodd"></path>
  </g>
  <path d="M32.998 298.562H5.371v-5.922l15.344-21.797H5.732v-7.734h26.875v5.89l-15.36 21.83h15.75Zm30.388 0H42.325v-35.453h21.063v7.687H51.902v5.578h10.625v7.688H51.902v6.688h11.485Zm44.233 0H95.055l-12.952-24.984h-.219c.082.761.157 1.68.22 2.75.062 1.062.116 2.152.171 3.265.05 1.106.078 2.102.078 2.985v15.984h-8.5v-35.453h12.516l12.906 24.64h.14c-.042-.78-.09-1.68-.14-2.703a195.75 195.75 0 0 1-.14-3.125 94.762 94.762 0 0 1-.047-2.78v-16.032h8.53Zm27.62-29.719c-1.449 0-2.726.282-3.828.844a7.766 7.766 0 0 0-2.797 2.422c-.75 1.043-1.32 2.308-1.703 3.797-.387 1.492-.578 3.168-.578 5.031 0 2.5.305 4.637.922 6.406.625 1.774 1.594 3.125 2.906 4.063 1.313.93 3.004 1.39 5.078 1.39 1.438 0 2.88-.16 4.328-.484 1.446-.332 3.02-.797 4.72-1.39v6.312a23.33 23.33 0 0 1-4.642 1.39c-1.523.282-3.226.422-5.109.422-3.656 0-6.664-.754-9.015-2.265-2.356-1.509-4.103-3.625-5.236-6.344-1.125-2.727-1.687-5.914-1.687-9.563 0-2.675.359-5.128 1.078-7.359.726-2.238 1.797-4.172 3.203-5.797 1.414-1.633 3.156-2.894 5.219-3.781 2.07-.895 4.453-1.344 7.14-1.344a21.7 21.7 0 0 1 5.297.672 25.96 25.96 0 0 1 5.078 1.828l-2.422 6.11a42.52 42.52 0 0 0-4.013-1.641c-1.336-.477-2.649-.719-3.938-.719Zm42.559 29.719-2.578-8.438h-12.922l-2.578 8.438h-8.094l12.516-35.61h9.187l12.563 35.61Zm-4.375-14.75-2.563-8.25-.656-2.11c-.262-.863-.527-1.742-.797-2.64a69.105 69.105 0 0 1-.64-2.344 56.032 56.032 0 0 1-.657 2.453c-.28.98-.554 1.907-.812 2.782-.262.875-.45 1.496-.563 1.859l-2.546 8.25Zm32.537-20.703c4.57 0 7.906.984 10 2.953 2.101 1.96 3.156 4.656 3.156 8.094 0 1.554-.234 3.039-.703 4.453a9.839 9.839 0 0 1-2.297 3.781c-1.055 1.094-2.46 1.965-4.219 2.61-1.761.636-3.937.953-6.53.953h-3.22v12.609h-7.514v-35.453Zm-.39 6.156h-3.423v10.531h2.47c1.405 0 2.624-.187 3.655-.562 1.04-.375 1.844-.957 2.407-1.75.57-.79.859-1.805.859-3.047 0-1.75-.492-3.047-1.469-3.89-.968-.852-2.468-1.282-4.5-1.282Zm38.19 29.297h-7.515v-29.203h-9.625v-6.25h26.766v6.25h-9.626Zm34.424-29.719c-1.449 0-2.726.282-3.828.844a7.766 7.766 0 0 0-2.797 2.422c-.75 1.043-1.32 2.308-1.703 3.797-.387 1.492-.578 3.168-.578 5.031 0 2.5.305 4.637.922 6.406.625 1.774 1.594 3.125 2.906 4.063 1.313.93 3.004 1.39 5.078 1.39 1.438 0 2.88-.16 4.328-.484 1.446-.332 3.02-.797 4.72-1.39v6.312a23.33 23.33 0 0 1-4.642 1.39c-1.523.282-3.226.422-5.109.422-3.656 0-6.664-.754-9.015-2.265-2.356-1.509-4.103-3.625-5.236-6.344-1.125-2.727-1.687-5.914-1.687-9.563 0-2.675.359-5.128 1.078-7.359.726-2.238 1.797-4.172 3.203-5.797 1.414-1.633 3.156-2.894 5.219-3.781 2.07-.895 4.453-1.344 7.14-1.344a21.7 21.7 0 0 1 5.297.672 25.96 25.96 0 0 1 5.078 1.828l-2.422 6.11a42.52 42.52 0 0 0-4.013-1.641c-1.336-.477-2.649-.719-3.938-.719Zm49.903 29.719h-7.486v-15.297h-14.046v15.297h-7.514v-35.453h7.515v13.89h14.047v-13.89h7.485Zm34.928 0-2.578-8.438h-12.922l-2.578 8.438h-8.094l12.516-35.61h9.187l12.563 35.61Zm-4.375-14.75-2.563-8.25-.656-2.11c-.262-.863-.527-1.742-.797-2.64a69.105 69.105 0 0 1-.64-2.344 56.032 56.032 0 0 1-.657 2.453c-.28.98-.554 1.907-.812 2.782-.262.875-.45 1.496-.563 1.859l-2.546 8.25Zm0 0"></path>
</svg>

</div>
</div>`;
		}, 10, 3
	);
}
}

window.zencaptchaAdminElementorPro = new zencaptchaAdminElementorPro();