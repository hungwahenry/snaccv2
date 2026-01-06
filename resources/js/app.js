import './bootstrap';

import Alpine from 'alpinejs';
import createSnaccForm from './components/createSnaccForm';
import gifPicker from './components/gifPicker';
import lightbox from './components/lightbox';
import otpInput from './components/otpInput';
import photoUpload from './components/photoUpload';
import searchableSelect from './components/searchableSelect';
import commentCard from './components/commentCard';
import commentsList from './components/commentsList';
import commentInput from './components/commentInput';

window.Alpine = Alpine;

// Register Alpine components
Alpine.data('createSnaccForm', createSnaccForm);
Alpine.data('gifPicker', gifPicker);
Alpine.data('lightbox', lightbox);
Alpine.data('otpInput', otpInput);
Alpine.data('photoUpload', photoUpload);
Alpine.data('searchableSelect', searchableSelect);
Alpine.data('commentCard', commentCard);
Alpine.data('commentsList', commentsList);
Alpine.data('commentInput', commentInput);

Alpine.start();
