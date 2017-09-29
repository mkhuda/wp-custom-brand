<?php 
function ___sanitize_term_meta_text ( $value ) {
    return sanitize_text_field ($value);
}

// GETTER (will be sanitized)
function ___get_term_meta_text( $term_id ) {
  $value = get_term_meta( $term_id, '__term_meta_text', true );
  return $value;
}

function ___get_term_brand_image( $term_id ) {
  $value = get_term_meta( $term_id, '__term_brand_image', true );
  return $value;
}

function ___get_term_brand_logo( $term_id ) {
  $value = get_term_meta( $term_id, '__term_brand_logo', true );
  return $value;
}

function ___get_term_brand_tagline( $term_id ) {
  $value = get_term_meta( $term_id, '__term_brand_tagline', true );
  return $value;
}
?>