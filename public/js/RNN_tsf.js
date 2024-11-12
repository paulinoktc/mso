

// Asegúrate de que tu código se ejecuta después de que la página se haya cargado

// Funciones para preparar los datos y crear el modelo
function createSequences(data, windowSize) {
    const inputs = [];
    const labels = [];

    // Recorre el array y crea secuencias con sus etiquetas
    for (let i = 0; i < data.length - windowSize; i++) {
        let inputSequence = data.slice(i, i + windowSize); // Obtiene la secuencia de entrada
        let label = data[i + windowSize]; // El valor siguiente que actúa como etiqueta
        inputs.push(inputSequence); // Añade la secuencia a las entradas
        labels.push(label); // Añade el valor siguiente a las etiquetas
    }

    return {
        inputs: inputs, // Devuelve las secuencias de entrada
        labels: labels // Devuelve las etiquetas correspondientes
    };
}


function createModel(windowSize) {
    const model = tf.sequential(); // Crea un modelo secuencial

    // Capa RNN
    model.add(tf.layers.simpleRNN({
        units: 30, // Número de neuronas en la capa RNN
        inputShape: [windowSize, 1], // es el tamaño de la secuencia de entrada, es decir, cuántos pasos temporales usará la red en cada predicción.
        returnSequences: false // Solo queremos la salida final, no todas las salidas de la secuencia
    }));

    // Capa de salida densa
    model.add(tf.layers.dense({
        units: 1 // Una neurona de salida (porque es una predicción única)
    }));

    model.compile({
        //optimizer: 'adam',
        optimizer: tf.train.sgd(0.01), // Usamos SGD con una tasa de aprendizaje de 0.01
        loss: 'meanSquaredError' // Función de pérdida para regresión
    });

    return model; // Retorna el modelo compilado
}

//normalizacion de los datos entre cero y uno
function normalizeData(data) {
    const min = Math.min(...data);
    const max = Math.max(...data);
    const normalizedData = data.map(value => (value - min) / (max - min));
    //console.log(normalizedData);

    return {
        normalizedData,
        min,
        max
    };
}
//vuelve a su forma original los valores ya no seran en rango cero a uno
function denormalizeData(value, min, max) {
    return value * (max - min) + min;
}

async function trainModel(model, inputs, labels) {
    // Convertir inputs y labels a tensores de TensorFlow.js
    const tensorInputs = tf.tensor2d(inputs, [inputs.length, inputs[0].length]) //Convierte los inputs (que son secuencias de datos) en un tensor 2D
        .reshape([inputs.length, inputs[0].length, 1]); //Cambia la forma del tensor de 2D a 3D
    const tensorLabels = tf.tensor2d(labels, [labels.length, 1]);

    // Entrenar el modelo
    await model.fit(tensorInputs, tensorLabels, {
        epochs: 50, // Número de veces que se pasan todos los datos por la red
        batchSize: 32 // Tamaño de los lotes (número de muestras por iteración)
    });

    console.log('Modelo entrenado');
}


function predictNextValues(model, lastWindow, numPredictions, min, max) {
    let predictions = [];

    let window = lastWindow.slice(); // Copia de la última ventana
    for (let i = 0; i < numPredictions; i++) {
        // Convertimos la ventana actual en un tensor y hacemos la predicción
        const inputTensor = tf.tensor2d([window], [1, window.length]).reshape([1, window.length, 1]);
        const prediction = model.predict(inputTensor);
        const predictedValue = prediction.dataSync()[0];

        // Desnormalizar el valor predicho
        const denormalizedValue = denormalizeData(predictedValue, min, max);

        // Agregar la predicción a la lista
        predictions.push(denormalizedValue);

        // Actualizar la ventana: eliminar el primer valor y agregar la predicción al final
        window.shift(); // Elimina el primer valor
        window.push(predictedValue); // Agrega la predicción como último valor
    }

    return predictions;
}


// Flujo completo
async function run(data) {
    // Parámetros
    const windowSize = 5;
    const numPredictions = 35; // Número de predicciones a realizar

    // Normalizar los datos
    const {
        normalizedData,
        min,
        max
    } = normalizeData(data);

    // Crear secuencias
    const {
        inputs,
        labels
    } = createSequences(normalizedData, windowSize);

    // Crear y entrenar el modelo
    const model = createModel(windowSize);
    await trainModel(model, inputs, labels);

    // Predicción de los siguientes 35 valores
    const lastWindow = normalizedData.slice(-windowSize);
    const nextValues = await predictNextValues(model, lastWindow, numPredictions, min, max);

    // Devolver los próximos valores predichos
    return nextValues;
}




