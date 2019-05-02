const elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {
    /*mix.sass('app.scss')
       .webpack('app.js');*/
       mix.scripts([
        	"custom/alert-popup.js",
        	"custom/footer.js",
        	"custom/form-validations.js",
        	"custom/login-popup.js",
        	"custom/select2.js",
        	"custom/tables.js",
        	"custom/tournament.schedule.open.js",
            "custom/page-return.js",
            "custom/score-edition.js",
            "custom/shiftMatch.js",
            "custom/import.js",
            "custom/close.pool.js",
           	"custom/profileCreateformValidate.js",
           "custom/profileChangeFormValidate.js",
		   	"custom/teamCreate.js"
    	]);
});
