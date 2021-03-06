/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/bookmarkForm.js":
/*!**************************************!*\
  !*** ./resources/js/bookmarkForm.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.onload = function (event) {
  // dynamically create tag list on form load.
  var tags = document.querySelector('#tags').value;

  if (tags) {
    tags.split(',').forEach(function (tag) {
      tagInputs.innerHTML += createTagMarkup(tag);
    });
  }
};

var bookmarkFormHandler = function bookmarkFormHandler(event) {
  event.preventDefault(); // gather up the tags...

  var inputTags = [];
  document.querySelectorAll('.tags-input > span').forEach(function (el) {
    return inputTags.push(el.textContent.trim());
  });
  var tagInput = document.querySelector('#tags');
  tagInput.value = inputTags.join(','); //... and submit the form

  bookmarkForm.submit();
};

var bookmarkFormKeyPressHandler = function bookmarkFormKeyPressHandler(event) {
  // prevent enter from submitting the form
  if (event.key === 'Enter') {
    event.preventDefault();
    return;
  }
};

var createTagMarkup = function createTagMarkup(tag) {
  return "<span class=\"badge badge-primary mr-2 mt-2 tag-input\">\n            ".concat(tag, "\n            <svg width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" class=\"bi bi-x\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\" onclick=\"this.parentElement.remove();\">\n                <path fill-rule=\"evenodd\" d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"/>\n            </svg>\n        </span>");
};

var addButtonClickHandler = function addButtonClickHandler(event) {
  event.preventDefault();
  addTagToForm();
};

var addInputEnterHandler = function addInputEnterHandler(event) {
  event.preventDefault(); // add tag up on select enter

  if (event.key === 'Enter') {
    addTagToForm();
  }
};

var addTagToForm = function addTagToForm() {
  var newTag = addTagInput.value;

  if (newTag.trim() === '') {
    return false;
  }

  tagInputs.innerHTML += createTagMarkup(newTag);
  addTagInput.value = '';
};

var bookmarkForm = document.querySelector('#bookmark-form');
var addTagButton = document.querySelector('#add-tag-button');
var addTagInput = document.querySelector('#add-tag-input');
var tagInputs = document.querySelector('.tags-input');
bookmarkForm.addEventListener('keypress', bookmarkFormKeyPressHandler);
bookmarkForm.addEventListener('submit', bookmarkFormHandler);
addTagButton.addEventListener('click', addButtonClickHandler);
addTagInput.addEventListener('keyup', addInputEnterHandler);

/***/ }),

/***/ 1:
/*!********************************************!*\
  !*** multi ./resources/js/bookmarkForm.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/joe/projects/kaboomr/resources/js/bookmarkForm.js */"./resources/js/bookmarkForm.js");


/***/ })

/******/ });