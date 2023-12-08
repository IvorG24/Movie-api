'use strict';

import { api_key, imageBaseURL, fetchDataFromServer } from "./api.js";
import { sidebar } from "./sidebar.js";
import { createMovieCard } from "./movie-card.js";
import { search } from "./search.js";


const movieId = window.localStorage.getItem("movieId");
const pageContent = document.querySelector("[page-content]");



sidebar();

const fetchWatchProviders = (movieId) => {
  const url = `https://api.themoviedb.org/3/movie/${movieId}/watch/providers?api_key=${api_key}`;
  console.log('Requesting URL:', url); // Debugging: Log the full URL

  fetchDataFromServer(url, function (providersData) {
    logProviders(providersData.results);
  });
};

const logProviders = (providers) => {
  const countryProviders = providers['US']; // Change 'US' to desired country code

  if (!countryProviders) {
    console.log("No providers found for this country.");
    return;
  }

  if (countryProviders.flatrate) {
    console.log('Streaming Providers:', countryProviders.flatrate.map(provider => provider.provider_name).join(", "));
  }

  if (countryProviders.rent) {
    console.log('Rental Providers:', countryProviders.rent.map(provider => provider.provider_name).join(", "));
  }

  if (countryProviders.buy) {
    console.log('Purchase Providers:', countryProviders.buy.map(provider => provider.provider_name).join(", "));
  }
};

const getGenres = function (genreList) {
  const newGenreList = [];

  for (const { name } of genreList) newGenreList.push(name);

  return newGenreList.join(", ");
}

const getCasts = function (castList) {
  const newCastList = [];

  for (let i = 0, len = castList.length; i < len && i < 10; i++) {
    const { name } = castList[i];
    newCastList.push(name);
  }

  return newCastList.join(", ");
}

const getDirectors = function (crewList) {
  const directors = crewList.filter(({ job }) => job === "Director");

  const directorList = [];
  for (const { name } of directors) directorList.push(name);

  return directorList.join(", ");
}

// returns only trailers and teasers as array
const filterVideos = function (videoList) {
  return videoList.filter(({ type, site }) => (type === "Trailer" || type === "Teaser") && site === "YouTube");
}



fetchDataFromServer(`https://api.themoviedb.org/3/movie/${movieId}?api_key=${api_key}&append_to_response=casts,videos,images,releases`, function (movie) {

  const {
    backdrop_path,
    poster_path,
    title,
    release_date,
    runtime,
    vote_average,
    releases: { countries: [{ certification } = { certification: "N/A" }] },
    genres,
    
    overview,
    casts: { cast, crew },
    videos: { results: videos }
  } = movie;
  if (certification === "R-18" || certification === "NC-17") {
    return null;
  }
  document.title = `${title} - Tvflix`;

  const movieDetail = document.createElement("div");
  movieDetail.classList.add("movie-detail");

  movieDetail.innerHTML = `
    <div class="backdrop-image" style="background-image: url('${imageBaseURL}${"w1280" || "original"}${backdrop_path || poster_path}')"></div>
    
    <figure class="poster-box movie-poster">
      <img src="${imageBaseURL}w342${poster_path}" alt="${title} poster" class="img-cover">
    </figure>
    
    <div class="detail-box">
    
      <div class="detail-content">
        <h1 class="heading">${title}</h1>
    
        <div class="meta-list">
    
          <div class="meta-item">
            <img src="./assets/images/star.png" width="20" height="20" alt="rating">
    
            <span class="span">${vote_average.toFixed(1)}</span>
          </div>
    
          <div class="separator"></div>
    
          <div class="meta-item">${runtime}m</div>
    
          <div class="separator"></div>
    
          <div class="meta-item">${release_date?.split("-")[0] ?? "Not Released"}</div>
    
          <div class="meta-item card-badge">${certification}</div>

          <div class="Linkbutton"><a href="https://www.smcinema.com/Ticketing/visShop.aspx?visLang=1&AspxAutoDetectCookieSupport=1"><Button> SM Cinemas</Btton></a></div>
          <div class="Linkbuttonrb"><a href="https://www.robinsonsmovieworld.com/"><Button>Movie World</Btton></a></div>
          <div class="Linkbuttonay"><a href="https://www.ayalamalls.com/watch/all/now-showing"><Button>Ayala Malls</Btton></a></div>
    
        </div>
    
        <p class="genre">${getGenres(genres)}</p>
    
        <p class="overview">${overview}</p>
    
        <ul class="detail-list">
    
          <div class="list-item">
            <p class="list-name">Starring</p>
    
            <p>${getCasts(cast)}</p>
          </div>
    
          <div class="list-item">
            <p class="list-name">Directed By</p>
    
            <p>${getDirectors(crew)}</p>
          </div>
    
        </ul>
    
      </div>
    
      <div class="title-wrapper">
        <h3 class="title-large">Trailers and Clips</h3>
      </div>
    
      <div class="slider-list">
        <div class="slider-inner"></div>
      </div>
    
    </div>
  `;

  for (const { key, name } of filterVideos(videos)) {
    const videoCard = document.createElement("div");
    videoCard.classList.add("video-card");

    videoCard.innerHTML = `
      <iframe width="500" height="294" src="https://www.youtube.com/embed/${key}?&theme=dark&color=white&rel=0"
        frameborder="0" allowfullscreen="1" title="${name}" class="img-cover" loading="lazy"></iframe>
    `;

    movieDetail.querySelector(".slider-inner").appendChild(videoCard);
  }

  pageContent.appendChild(movieDetail);

  fetchDataFromServer(`https://api.themoviedb.org/3/movie/${movieId}/recommendations?api_key=${api_key}&page=1`, addSuggestedMovies);

});



const addSuggestedMovies = function ({ results: movieList }, title) {

  const movieListElem = document.createElement("section");
  movieListElem.classList.add("movie-list");
  movieListElem.ariaLabel = "You May Also Like";

  movieListElem.innerHTML = `
    <div class="title-wrapper">
      <h3 class="title-large">You May Also Like</h3>
    </div>
    
    <div class="slider-list">
      <div class="slider-inner"></div>
    </div>
  `;

  for (const movie of movieList) {
    const movieCard = createMovieCard(movie); // called from movie_card.js

    movieListElem.querySelector(".slider-inner").appendChild(movieCard);
  }

  pageContent.appendChild(movieListElem);

}



search();