'use strict';

import { api_key, fetchDataFromServer } from "./api.js";

fetchDataFromServer(`https://api.themoviedb.org/3/movie/movie_id/watch/providers?api_key=${api_key}`, function ({ movies }) {
    for (const { id, name } of movies) {
      movies[id] = name;
    }

    movieLink();
  });


  