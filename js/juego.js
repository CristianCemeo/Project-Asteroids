// Creamos el objeto Phaser y le damos la anchura y altura que va a tener el juego, el motor de renderizado (Canvas, WebGL o automatico), 
// y en qué elemento del HTML se va a incluir
var juego = new Phaser.Game(400, 600, Phaser.AUTO, 'juego', {
    preload: preload,
    create: create,
    update: update
});

// Carga las imagenes, sonidos, fondos, ...
function preload() {

    juego.load.image('fondo', 'assets/galaxia.png');
    juego.load.spritesheet('nave', 'assets/nave.png', 26, 35);
    juego.load.spritesheet('explosion', 'assets/explosion.png', 80, 80);
    juego.load.image('asteroide', 'assets/asteroide.png', 70, 70);
    juego.load.audio('sonidoExplosion', 'assets/explosion.wav');
    juego.load.audio('musicaFondo', 'assets/musica-fondo.mp3');

}

//Tiempos y velocidades (milisegundos)
var tiempoPuntuacion = 1000;
var tiempoAsteroides = 1000;
var tiempoJuego = 5000;
var velocidadAsteroide = 100;

var nave;
var explosion;
var sonidoExplosion;
var puntuacion = 0;
var puntuacionFinal;
var txtFinal;
var txtPausa;
var txtEmpezar;
var reanudarJuego;
var movimientoNave = 120;
var movimientoNaveAtras = 100;

// Loops

var loopNuevaVelocidad = [], loopSubirVelocidad, loopCrearAsteroide;

// Carga los componentes del juego
function create() {

    //Añadimos las imagenes, sprites y sonido
    fondoGalaxia = juego.add.tileSprite(-400, -200, 800, 800, 'fondo');

    nave = juego.add.sprite(175, juego.world.height - 150, 'nave');

    sonidoExplosion = juego.add.audio('sonidoExplosion');
    musica = juego.add.audio('musicaFondo');

    //Reproducimos indefinidamente la musica
    musica.loop = true;
    musica.play();

    //Activamos las fisicas y que la nave no pueda salirse del mundo
    juego.physics.arcade.enable(nave);
    nave.body.collideWorldBounds = true;

    // Creamos un grupo que seran los asteroides
    asteroides = juego.add.group();
    asteroides.enableBody = true;

    // Agregamos animaciones a la nave
    nave.animations.add('left', [0, 1], 10, true);
    nave.animations.add('right', [3, 4], 10, true);
    nave.animations.add('stop', [2], 10, true);

    // Creamos eventos para aumentar el tiempo, crear los asteroides y sumar la puntuacion
    loopSubirVelocidad = juego.time.events.loop(tiempoJuego, aumentarVelocidad, this);
    loopCrearAsteroide = juego.time.events.loop(tiempoAsteroides, crearAsteroide, this);
    juego.time.events.loop(tiempoPuntuacion, sumarPuntuacion, this);

    // Añadir puntuacion
    txtPuntuacion = juego.add.text(10, 10, 'Puntos: ' + puntuacion, { font: '20px Arial', fill: '#fff' });
    
    // Añádir pausa
    txtPausa = juego.add.text(330, 10, 'Pausa', { font: '20px Arial', fill: '#fff' });
    reanudarJuego = juego.add.text(135, 200, 'Pausado', { font: '30px Arial', fill: '#fff' });
    reanudarJuego.visible = false;
    txtPausa.inputEnabled = true;
    // Cuando clickemos en el texto pausa se pausara el juego
    txtPausa.events.onInputUp.add(function () {
        juego.paused = true;
        reanudarJuego.visible = true;
    });
    // Al clickar en cualquier zona del juego se reanudara
    juego.input.onDown.add(function(){
        juego.paused = false;
        reanudarJuego.visible = false;
        txtEmpezar.visible = false;
    }, self);
    
    //Al cargar el juego empezará pausado y habra que clickar en él para empezar
    juego.paused = true;
    txtEmpezar = juego.add.text(115, 120, 'Empezar\n\nControles:\n↑ ↓ → ←', { font: '36px Arial', fill: '#fff' });

}

// Cambios o acciones del juego (presionar una tecla, mover el cursor...)
function update() {

    // Movemos el fondo
    fondoGalaxia.tilePosition.y += 1.5;

    juego.physics.arcade.overlap(nave, asteroides, juegoAcabado, null, this);

    // Creamos el movimiento al pulsar las teclas direccionales
    var teclas = juego.input.keyboard.createCursorKeys();

    // Ponemos a 0 la velocidad al tocar la tecla
    nave.body.velocity.y = 0;
    nave.body.velocity.x = 0;

    // Al pulsar la teclas direccionales
    if (teclas.left.isDown) {
        nave.body.velocity.x -= movimientoNave;    // A la izquierda
        nave.animations.play('left');
    } else if (teclas.right.isDown) {
        nave.body.velocity.x += movimientoNave;    // A la derecha
        nave.animations.play('right');
    } else {
        nave.animations.stop();         // Al no pulsar se para
        //nave.animations.play('stop');
    }

    if (teclas.up.isDown) {
        nave.body.velocity.y -= movimientoNave;    // Hacia arriba
    } else if (teclas.down.isDown) {
        nave.body.velocity.y += movimientoNaveAtras;    // Hacia abajo
    } else {
        nave.animations.stop();         // Al no pulsar se para
    }

}

function crearAsteroide() {

    // Creamos un asteroide en una posicion aleatoria arriba del todo (-50) del juego
    var asteroide = asteroides.create(Math.random() * 320, -70, 'asteroide');

    juego.physics.arcade.enable(asteroide);

    asteroide.body.velocity.y = velocidadAsteroide;

}

function sumarPuntuacion() {

    puntuacion++;
    txtPuntuacion.text = "Puntos: " + puntuacion;

}

var nuevoTiempoAsteroides = 2000;
var indice = 0;
function aumentarVelocidad() {

    velocidadAsteroide = velocidadAsteroide + 15;

    loopNuevaVelocidad[indice] = juego.time.events.loop(nuevoTiempoAsteroides, crearAsteroide, this);
    indice++;

    nuevoTiempoAsteroides = nuevoTiempoAsteroides - 75;
    
}

function juegoAcabado() {

    //asteroide.kill();

    // Borramos los eventos
    for (let i = 0; i < loopNuevaVelocidad.length; i++) {
        juego.time.events.remove(loopNuevaVelocidad[i]);
    }
    juego.time.events.remove(loopSubirVelocidad);
    juego.time.events.remove(loopCrearAsteroide);

    // Reproducimos el sonido de la explosión
    sonidoExplosion.play();

    // Creamos la explosion en la posicion que estaba la nave
    explosion = juego.add.sprite(nave.body.x-20, nave.body.y-30, 'explosion');
    explosion.animations.add('explosion', [0, 24], 4, false);
    explosion.animations.play('explosion');

    // Al pulsar en el juego se nos ejecuta la funcion reiniciarJuego
    juego.input.onTap.addOnce(reiniciarJuego, this);

    // Dejamos que se ejecute la animacion de la explosión y la borramos
    setInterval(() => {
        explosion.kill()
    }, 800);

    // Quitamos la nave y la puntuacion
    nave.kill();
    txtPuntuacion.visible = false;
    txtPausa.visible = false;

    // Guardamos puntuacion
    puntuacionFinal = puntuacion;
    agregarPuntuacion(puntuacionFinal);

    // Imprimimos texto final
    txtFinal = juego.add.text(70, 220, 'Perdiste. \nPuntuación: ' + puntuacionFinal + '\nClick para reiniciar. ', { font: '30px Arial', fill: '#fff', fontColor: 'red' });

    // Paramos la música
    musica.stop();

}

function reiniciarJuego() {

    // Reinciamos valores
    puntuacion = -1;
    puntuacionFinal = 0;
    tiempoAsteroides = 1000;
    velocidadAsteroide = 100;

    // Borramos asteroides y los volvemos a crear
    asteroides.removeAll();

    // Creamos la nave y la posicionamos
    nave.revive();
    nave.position.x=175;
    nave.position.y=400;

    // Iniciamos los eventos
    loopSubirVelocidad = juego.time.events.loop(tiempoJuego, aumentarVelocidad, this);
    loopCrearAsteroide = juego.time.events.loop(tiempoAsteroides, crearAsteroide, this);

    // Ocultamos texto final y ponemos puntuacion
    txtFinal.visible = false;
    txtPuntuacion.visible = true;
    txtPausa.visible = true;

    // Reproducimos la música
    musica.play();

}

function agregarPuntuacion(puntuacionFinal){
    $.ajax({
        type: "GET",
        url: "https://project-asteroids.000webhostapp.com/inc/agregarPuntuacion.php",
        data: "puntuacion=" + puntuacionFinal
    });
}
