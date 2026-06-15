<?php
/**
 * Header del tema: <head>, apertura <body>, header sticky con navigazione.
 *
 * @package PettenoTours
 */

// Voci di menu generate dalle sezioni attive (vedi functions.php).
$petteno_links = petteno_tours_nav_links();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#servizi" class="sr-only"><?php esc_html_e( 'Vai al contenuto principale', 'petteno-tours' ); ?></a>

<header class="site-header">
	<div class="wrap header-inner">
		<a href="#top" class="brand" aria-label="<?php esc_attr_e( 'Pettenò Tours — home', 'petteno-tours' ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<img
					src="<?php echo esc_url( petteno_tours_img( 'logo.png' ) ); ?>"
					alt="Pettenò Tours"
					class="brand-logo"
					onerror="this.style.display='none';document.getElementById('brand-fallback-h').style.display='flex'"
				/>
				<span class="brand-text" id="brand-fallback-h" style="display:none">
					<strong>Pettenò</strong>
					<span>Tours</span>
				</span>
			<?php endif; ?>
		</a>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Navigazione principale', 'petteno-tours' ); ?>">
			<?php foreach ( $petteno_links as $href => $label ) : ?>
				<a href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>

		<a href="#contatti" class="btn btn-primary header-cta"><?php esc_html_e( 'Richiedi preventivo', 'petteno-tours' ); ?></a>

		<button class="nav-toggle" aria-expanded="false" aria-controls="mobile-nav" aria-label="<?php esc_attr_e( 'Apri menù', 'petteno-tours' ); ?>">
			<span></span><span></span><span></span>
		</button>
	</div>

	<nav id="mobile-nav" class="mobile-nav" aria-label="<?php esc_attr_e( 'Navigazione mobile', 'petteno-tours' ); ?>" hidden>
		<?php foreach ( $petteno_links as $href => $label ) : ?>
			<a href="<?php echo esc_url( $href ); ?>" class="mobile-link"><?php echo esc_html( $label ); ?></a>
		<?php endforeach; ?>
		<a href="#contatti" class="btn btn-primary mobile-cta"><?php esc_html_e( 'Richiedi preventivo', 'petteno-tours' ); ?></a>
	</nav>
</header>
