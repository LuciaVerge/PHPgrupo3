// Añade un event listener al botón con ID 'buscarButton'
document.getElementById('buscarButton').addEventListener('click', function() {
    const query = document.getElementById('buscarInput').value;   // Obtiene el valor del input con ID 'buscarInput'
    if (query) {                                                  // Si el input no está vacío, llama a la función searchMovies con el valor del input
        searchMovies(query);
    }
});

// Función asincrónica para buscar películas en TMDb usando la API
async function searchMovies(query) {
    const apiKey = '2ed0ad3f429b26d99eda73097a1542d7'; 
    const url = `https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&query=${encodeURIComponent(query)}`;   // Construye la URL de la API con la clave de API y el término de búsqueda
    
    try {
        const response = await fetch(url);     // Realiza una llamada fetch a la URL de la API y espera la respuesta
        const data = await response.json();    // Convierte la respuesta a formato JSON
        displayResults(data.results);          // Llama a la función displayResults con los resultados de la búsqueda
    } catch (error) {
        console.error('Error fetching data:', error);   // Imprime un mensaje de error en la consola si ocurre un error
    }
}

// Función para mostrar los resultados de la búsqueda en la página web
function displayResults(movies) {                           
    const results = document.getElementById('results');     // Obtiene el elemento con ID 'results'
    results.innerHTML = '';                                 // Limpia el contenido del elemento 'results'

    if (movies.length === 0) {                                        // Si no hay resultados, muestra un mensaje indicando que no se encontraron resultados
        results.innerHTML = '<p id="sinDatos">No se encontraron resultados.</p>';
        return;
    }
    movies.forEach(movie => {                                        // Itera sobre cada película en el array de resultados
        const movieElement = document.createElement('div');          // Crea un contenedor para cada película
        movieElement.classList.add('movie');

        const moviePoster = document.createElement('img');           // Crea un elemento de imagen para el póster de la película
                // Establece la fuente de la imagen, usa una imagen propia si no hay póster disponible
                moviePoster.src = movie.poster_path ? `https://image.tmdb.org/t/p/w200${movie.poster_path}` : '../assets/img/placeholder.png';   
                moviePoster.alt = movie.title;

                const movieDetails = document.createElement('div');   // Crea un contenedor para los detalles de la película
                movieDetails.classList.add('movie-details');

                const movieTitle = document.createElement('h2');     // Crea un elemento de encabezado para el título de la película
                movieTitle.textContent = movie.title;

                const movieOverview = document.createElement('p');   // Crea un elemento de párrafo para la descripción de la película
                movieOverview.textContent = movie.overview;

                movieDetails.appendChild(movieTitle);        // Añade el título y la descripción al contenedor de detalles de la película
                movieDetails.appendChild(movieOverview);
                movieElement.appendChild(moviePoster);       // Añade el póster y los detalles al contenedor de la película
                movieElement.appendChild(movieDetails);
                results.appendChild(movieElement);           // Añade el contenedor de la película al elemento 'results'
    });
}