<!DOCTYPE html>
 <html lang="es">
 <head>
 <meta charset="utf-8">
 <script src="https://unpkg.com/graphre@0.1.3/dist/graphre.js"></script>
 <script src="https://unpkg.com/nomnoml@1.5.1/dist/nomnoml.js"></script>
 </head>
 <body>

    <canvas id="target-canvas"></canvas>
    <script>
        var canvas = document.getElementById('target-canvas');
        const vigencia = {{$vigencia}};
        const idRadicado = {{$idRadicado}};
        const etapa = {{$etapaActual}};
        const pasoDecPruJuz = {{$pasoDecPruJuz}};
        const pasoFalPrimIns = {{$pasoFalPrimIns}};
        const pasoAvoConJuz = {{$pasoAvoConJuz}};
        const pasoSolVarPli = {{$pasoSolVarPli}};
        const pasoTrasAleg = {{$pasoTrasAleg}};
        const pasoConRecApe = {{$pasoConRecApe}};
        const pasoAutVarPliCar = {{$pasoAutVarPliCar}};
        const pasoRemSegIns = {{$pasoRemSegIns}};
        const pasoRemExpCdi = {{$pasoRemExpCdi}};
        const pasoRecExpVarCar = {{$pasoRecExpVarCar}};
        const pasoAutVarPliCarJuz = {{$pasoAutVarPliCarJuz}};
        const pasoNoVarPliCar = {{$pasoNoVarPliCar}};
        const pasoRemFunJuz = {{$pasoRemFunJuz}};
        const pasoTraVarPliCar = {{$pasoTraVarPliCar}};
        const pasoPruVarJuz = {{$pasoPruVarJuz}};
        const pasoPruVar = {{$pasoPruVar}};
        const pasoNulPliCar = {{$pasoNulPliCar}};

const config = `
#arrowSize: 0.9
#bendSize: 0.1
#direction: right
#padding: 4
#spacing: 30
#zoom: 0.9
#lineWidth: 1
#fill: #f8fdff; #fff
#font: Calibri
#fontSize: 11
#stroke: #444
#.box: fill=#ffc107 stroke=#fff title=left,bold
#.verde: fill=#03d47d stroke=#fff`;

const root = `
[<frame>Procedimiento Fase de juzgamiento (Ordinario) - Proceso ${vigencia}-${idRadicado}|
    [<start>st]--[${etapa == 13 ? `<box>` : pasoAvoConJuz == 1 ? `<verde>` : ''} Avoca Conocimiento|.Decide el procedimiento a seguir \n .Traslado de descargos]`;

        console.log(pasoAvoConJuz, etapa)

const nodos = `
[${etapa == 6 ? `<box>` : pasoDecPruJuz == 1 ? `<verde>` : ''} Decreta Pruebas en Juzgamiento| Art. 225C]
[${etapa == 8 ? `<box>` : pasoFalPrimIns == 1 ? `<verde>` : ''} Fallo Primera Instancia|Art. 225F]
[${etapa == 17 ? `<box>` : pasoSolVarPli == 1 ? `<verde>` : ''} Auto solicita variación pliego de cargos|(Error en la calificación)]
[${etapa == 25 ? `<box>` : pasoTrasAleg == 1 ? `<verde>` : ''} Traslado de Alegatos|Art. 225E]
[${etapa == 26 ? `<box>` : pasoConRecApe == 1 ? `<verde>` : ''} Concede Recurso de Apelación|Art. 226G]
[${etapa == 30 ? `<box>` : pasoAutVarPliCarJuz == 1 ? `<verde>` : ''} Auto varía pliego de cargos|Num. 1, Art. 225D]
[${etapa == 31 ? `<box>` : pasoRemSegIns == 1 ? `<verde>` : ''} Remisión a Segunda Instancia]
[${etapa == 32 ? `<box>` : pasoRemExpCdi == 1 ? `<verde>` : ''} Remisión Expediente a CDI]
[${etapa == 33 ? `<box>` : pasoRecExpVarCar == 1 ? `<verde>` : ''} Recibe expediente para variación de cargos|Num. 1-3, Art. 225D]
[${etapa == 34 ? `<box>` : pasoAutVarPliCar == 1 ? `<verde>` : ''} Auto que Varía Pliego de Cargos]
[${etapa == 35 ? `<box>` : pasoNoVarPliCar == 1 ? `<verde>` : ''} No Varía Pliego de Cargos|Num. 3, Art. 225D]
[${etapa == 36 ? `<box>` : pasoRemFunJuz == 1 ? `<verde>` : ''} Remite a funcionario de juzgamiento|Num 2 y 3, Art. 225D]
[${etapa == 37 ? `<box>` : pasoTraVarPliCar == 1 ? `<verde>` : ''} Traslado variación pliego de cargos|Num. 5, Art. 225D]
[${etapa == 38 ? `<box>` : pasoPruVarJuz == 1 ? `<verde>` : ''} Pruebas Variación Juzgador]
[${etapa == 39 ? `<box>` : pasoPruVar == 1 ? `<verde>` : ''} Pruebas Variación]
[${etapa == 40 ? `<box>` : pasoNulPliCar == 1 ? `<verde>` : ''} Nulidad del pliego de cargos|Num 3, Art. 225]`;

const decisiones = `
    [Avoca Conocimiento] -> [<choice>Variación?]
    [Variación?] sí ->[Auto solicita variación pliego de cargos]
    [Variación?] no -> [<choice>Decreta Pruebas?]

    [Decreta Pruebas?] sí ->[Decreta Pruebas en Juzgamiento]
    [Decreta Pruebas?] no -> [<choice>Varía Pliegos?]

    [Varía Pliegos?] sí ->[Auto varía pliego de cargos]
    [Varía Pliegos?] no ->[Traslado de Alegatos]

    [Fallo Primera Instancia] -> [<choice>Recurso?]
    [Recurso?] sí ->[Concede Recurso de Apelación]
    [Recurso?] no ->[Remisión Expediente a CDI]

    [Recibe expediente para variación de cargos] -> [<choice>Varía Pliego?]
    [Varía Pliego?] sí ->[Auto que Varía Pliego de Cargos]
    [Varía Pliego?] no ->[No Varía Pliego de Cargos]

    [Remite a funcionario de juzgamiento] -> [<choice>Varió Pliego?]
    [Varió Pliego?] sí ->[Traslado variación pliego de cargos]
    [Varió Pliego?] no -> [<choice>Nulidad?]

    [Nulidad?] sí ->[Nulidad del pliego de cargos]
    [Nulidad?] no ->[Decreta Pruebas en Juzgamiento]

    `;
    
const uniones = `
    [Decreta Pruebas en Juzgamiento] -> [<choice>Varía Pliegos?]
    [Auto varía pliego de cargos] -> [Pruebas Variación Juzgador]
    [Pruebas Variación Juzgador] -> [Traslado de Alegatos]
    [Traslado de Alegatos] -> [Fallo Primera Instancia]
    [Concede Recurso de Apelación] -> [Remisión a Segunda Instancia]
    [Auto solicita variación pliego de cargos] -> [Recibe expediente para variación de cargos]
    [Auto que Varía Pliego de Cargos] -> [Remite a funcionario de juzgamiento]
    [No Varía Pliego de Cargos] -> [Remite a funcionario de juzgamiento]
    [Traslado variación pliego de cargos] -> [Pruebas Variación]
    [Pruebas Variación] -> [Traslado de Alegatos]
    [Nulidad del pliego de cargos] -> [Remisión Expediente a CDI]    

    [Remisión a Segunda Instancia] ->[<end>e]
    [Remisión Expediente a CDI] ->[<end>e]
]
`;
    
 nomnoml.draw(canvas, config+root+nodos+decisiones+uniones);
 </script>
 </body>
</html>