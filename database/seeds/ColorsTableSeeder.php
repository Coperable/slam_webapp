<?php
use Illuminate\Database\Seeder;
use Slam\User;
use Slam\Model\Icon;
use Slam\Model\Color;
use Illuminate\Support\Facades\Hash;

class ColorsTableSeeder extends Seeder {

    public function run() {
        DB::table('icons')->delete();
        DB::table('colors')->delete();

        $color = new Color();
        $color->code ="#ff1945";
        $color->description ="#ff1945";
        $color->save();

        $color = new Color();
        $color->code ="#ff6555";
        $color->description ="#ff6555";
        $color->save();

        $color = new Color();
        $color->code ="#cd4e00";
        $color->description ="#cd4e00";
        $color->save();

        $color = new Color();
        $color->code ="#f58414";
        $color->description ="#f58414";
        $color->save();

        $color = new Color();
        $color->code ="#ffb204";
        $color->description ="#ffb204";
        $color->save();

        $color = new Color();
        $color->code ="#ffdd19";
        $color->description ="#ffdd19";
        $color->save();

        $color = new Color();
        $color->code ="#dac900";
        $color->description ="#dac900";
        $color->save();

        $color = new Color();
        $color->code ="#b8da00";
        $color->description ="#b8da00";
        $color->save();

        $color = new Color();
        $color->code ="#a1ba19";
        $color->description ="#a1ba19";
        $color->save();

        $color = new Color();
        $color->code ="#9dd637";
        $color->description ="#9dd637";
        $color->save();

        $color = new Color();
        $color->code ="#11b421";
        $color->description ="#11b421";
        $color->save();

        $color = new Color();
        $color->code ="#35d286";
        $color->description ="#35d286";
        $color->save();

        $color = new Color();
        $color->code ="#30b294";
        $color->description ="#30b294";
        $color->save();

        $color = new Color();
        $color->code ="#3f93a1";
        $color->description ="#3f93a1";
        $color->save();

        $color = new Color();
        $color->code ="#287dc2";
        $color->description ="#287dc2";
        $color->save();

        $color = new Color();
        $color->code ="#7288d5";
        $color->description ="#7288d5";
        $color->save();

        $color = new Color();
        $color->code ="#8329c1";
        $color->description ="#8329c1";
        $color->save();

        $color = new Color();
        $color->code ="#bd5ca0";
        $color->description ="#bd5ca0";
        $color->save();

        $color = new Color();
        $color->code ="#ff5a8c";
        $color->description ="#ff5a8c";
        $color->save();


        $icono = new Icon();
        $icono->code = "icon-comentarios";
        $icono->description = "icon-comentarios";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-video";
        $icono->description = "icon-video";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-slam-x-o";
        $icono->description = "icon-slam-x-o";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-slam-x";
        $icono->description = "icon-slam-x";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-slam-black";
        $icono->description = "icon-slam-black";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-slam-arg";
        $icono->description = "icon-slam-arg";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-slam";
        $icono->description = "icon-slam";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-cordoba";
        $icono->description = "icon-provincia-cordoba";
        $icono->is_region = true;
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-entrerios";
        $icono->is_region = true;
        $icono->description = "icon-provincia-entrerios";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-formosa";
        $icono->is_region = true;
        $icono->description = "icon-provincia-formosa";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-jujuy";
        $icono->is_region = true;
        $icono->description = "icon-provincia-jujuy";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-lapampa";
        $icono->is_region = true;
        $icono->description = "icon-provincia-lapampa";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-larioja";
        $icono->is_region = true;
        $icono->description = "icon-provincia-larioja";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-malvinas";
        $icono->is_region = true;
        $icono->description = "icon-provincia-malvinas";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-mendoza";
        $icono->is_region = true;
        $icono->description = "icon-provincia-mendoza";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-misiones";
        $icono->is_region = true;
        $icono->description = "icon-provincia-misiones";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-neuquen";
        $icono->is_region = true;
        $icono->description = "icon-provincia-neuquen";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-rionegro";
        $icono->is_region = true;
        $icono->description = "icon-provincia-rionegro";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-salta";
        $icono->is_region = true;
        $icono->description = "icon-provincia-salta";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-sanjuan";
        $icono->is_region = true;
        $icono->description = "icon-provincia-sanjuan";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-sanluis";
        $icono->is_region = true;
        $icono->description = "icon-provincia-sanluis";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-santacruz";
        $icono->is_region = true;
        $icono->description = "icon-provincia-santacruz";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-santafe";
        $icono->is_region = true;
        $icono->description = "icon-provincia-santafe";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-stgodelestero";
        $icono->is_region = true;
        $icono->description = "icon-provincia-stgodelestero";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-tierradelfuego";
        $icono->is_region = true;
        $icono->description = "icon-provincia-tierradelfuego";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-tucuman";
        $icono->is_region = true;
        $icono->description = "icon-provincia-tucuman";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-revista";
        $icono->description = "icon-revista";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-chubut";
        $icono->is_region = true;
        $icono->description = "icon-provincia-chubut";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-chaco";
        $icono->is_region = true;
        $icono->description = "icon-provincia-chaco";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-catamarca";
        $icono->is_region = true;
        $icono->description = "icon-provincia-catamarca";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-capital";
        $icono->is_region = true;
        $icono->description = "icon-provincia-capital";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-provincia-ba";
        $icono->is_region = true;
        $icono->description = "icon-provincia-ba";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-prototipo";
        $icono->description = "icon-prototipo";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-notificaciones";
        $icono->description = "icon-notificaciones";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-mencion";
        $icono->description = "icon-mencion";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-mapa";
        $icono->description = "icon-mapa";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-jugadores";
        $icono->description = "icon-jugadores";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-jugador";
        $icono->description = "icon-jugador";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-inicio";
        $icono->description = "icon-inicio";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-hashtag";
        $icono->description = "icon-hashtag";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-foto";
        $icono->description = "icon-foto";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-copa-3";
        $icono->description = "icon-copa-3";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-copa-2";
        $icono->description = "icon-copa-2";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-copa-1";
        $icono->description = "icon-copa-1";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-copa";
        $icono->description = "icon-copa";
        $icono->save();

        $icono = new Icon();
        $icono->code = "icon-comentarios-o";
        $icono->description = "icon-comentarios-o";
        $icono->save();




     }

}





