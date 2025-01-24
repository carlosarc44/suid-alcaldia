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

        const pasoInhbit = {{$pasoInhbit}};
        const pasoIndPre = {{$pasoIndPre}};
        const pasoInvDis = {{$pasoInvDis}};
        const pasoProUno = {{$pasoProUno}};
        const pasoProDos = {{$pasoProDos}};
        const pasoCieInv = {{$pasoCieInv}};
        const pasoPliCar = {{$pasoPliCar}};
        const pasoNotPli = {{$pasoNotPli}};
        const pasoRemJuz = {{$pasoRemJuz}};
        const pasoArchiv = {{$pasoArchiv}};

const config = `
#arrowSize: 0.9
#bendSize: 0.1
#direction: right
#fillArrows: false
#leading: 1.1
#padding: 4
#spacing: 30
#zoom: 0.85
#lineWidth: 1
#fill: #f8fdff; #fff
#font: Calibri
#fontSize: 11
#stroke: #444
#.box: fill=#ffc107 stroke=#fff title=left,bold
#.verde: fill=#03d47d stroke=#fff`;

const root = `
[<frame>Procedimiento Fase de Instrucción - Proceso ${vigencia}-${idRadicado}|
    [<start>st]--[Radicación|.De oficio \n .Queja \n .Informe \n .Exp. Anónimo]`;

const nodos = `
    [${etapa == 9 ? `<box>` : pasoInhbit == 1 ? `<verde>` : ''}Inhibitorio|Art. 209]        
    [${etapa == 1 ? `<box>` : pasoIndPre == 1 ? `<verde>` : ''}Indagación Previa|Art. 208]
    [${etapa == 2 ? `<box>` : pasoInvDis == 1 ? `<verde>` : ''}Investigación Disciplinaria|Art. 213]
    [${etapa == 3 ? `<box>` : pasoProUno == 1 ? `<verde>` : ''}Prórroga 1|6 meses|Inc. 1 Art. 213]
    [${etapa == 27 ? `<box>` : pasoProDos == 1 ? `<verde>` : ''}Prórroga 2|3 meses|Inc. 3 Art. 213]
    [${etapa == 24 ? `<box>` : pasoCieInv == 1 ? `<verde>` : ''}Cierre Investigación|Traslado|Alegatos previos|Art. 220]
    [${etapa == 5 ? `<box>` : pasoPliCar == 1 ? `<verde>` : ''}Pliego de cargos|Art. 222]
    [${etapa == 29 ? `<box>` : pasoRemJuz == 1 ? `<verde>` : ''}Remisión Juzgamiento|Inc. 4 Art. 225]
    [${etapa == 10 ? `<box>` : pasoArchiv == 1 ? `<verde>` : ''}Archivo|Par 1. Art. 208|Inc. 3 Art. 213|Art. 221]`;

const decisiones = `
    [<verde>Radicación|.De oficio \n .Queja \n .Informe \n .Exp. Anónimo|Art. 86] -> [<choice>Inhibirse?]
    [Inhibirse?] sí ->[Inhibitorio]
    [Inhibirse?] no ->[Indagación Previa]

    [Indagación Previa] -> [<choice>Archiva?]
    [Archiva?] sí ->[Archivo]
    [Archiva?] no ->[Investigación Disciplinaria]

    [Investigación Disciplinaria] -> [<choice>Archiva.?]
    [Archiva.?] sí ->[Archivo]
    [Archiva.?] no ->[<choice>Prórroga?]
        
    [Prórroga?] sí ->[Prórroga 1]
    [Prórroga?] no ->[Cierre Investigación]

    [Prórroga 1] -> [<choice>Archiva ?]
    [Archiva ?] sí ->[Archivo]
    [Archiva ?] no ->[<choice>+ Prórroga?]

    [<choice>Archiva ?] -> [<choice>+ Prórroga?]
    [+ Prórroga?] sí ->[Prórroga 2]
    [+ Prórroga?] no ->[Cierre Investigación]    

    [Prórroga 2] -> [<choice>Arch. Definitivo?]
    [Arch. Definitivo?] sí ->[Archivo]
    [Arch. Definitivo?] no ->[Cierre Investigación]

    [Cierre Investigación] -> [<choice>Archivo?]
    [Archivo?] sí ->[Archivo]
    [Archivo?] no ->[Pliego de cargos]`;
    
const uniones = `
    [Pliego de cargos] -> [Remisión Juzgamiento|Inc. 4 Art. 225]
    [Archivo] -> [<end>e]
]
`;
    
 nomnoml.draw(canvas, config+root+nodos+decisiones+uniones);
 </script>
 </body>
</html>