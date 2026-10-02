<?php
/**
 * Hope Market - first products.
 *
 * Products photographed by the Foundation, bundled with the theme in
 * assets/images/shop/ as 1200x1200 squares so they sit evenly in the
 * WooCommerce grid (WooCommerce crops thumbnails to 1:1).
 *
 * Each product is created once, the first time an administrator opens
 * wp-admin with WooCommerce active. After that the product belongs to the
 * shop: edit the name, price, description or photo under Products and this
 * file never touches it again. Deleting a product is also permanent - it is
 * not recreated.
 *
 * Prices are the selling prices the Foundation supplied (KES). Names and
 * descriptions describe only what is visible in the photographs.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Seed definitions, keyed by a stable id.
 *
 * @return array<string,array<string,mixed>>
 */
function cohf_shop_seed_products() {
	$design_note = __( 'Pictured is a selection of the designs available. Each pair is handmade, so beadwork and colours vary. Add your preferred design and shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' );

	return array(
		'beaded-leather-sandals' => array(
			'name'     => __( 'Beaded Leather Sandals', 'cohf-child' ),
			'price'    => '1000',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'beaded-leather-sandals.jpg',
			'alt'      => __( 'Rows of flat tan leather sandals decorated with colourful handmade beadwork.', 'cohf-child' ),
			'short'    => __( 'Flat tan leather sandals with hand-stitched beadwork on the straps.', 'cohf-child' ),
			'long'     => $design_note,
			'order'    => 1,
		),
		'beaded-wedge-sandals'   => array(
			'name'     => __( 'Beaded Wedge Sandals', 'cohf-child' ),
			'price'    => '3000',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'beaded-wedge-sandals.jpg',
			'alt'      => __( 'Wedge sandals with cushioned soles and beaded straps in gold, white, blue and multicolour designs.', 'cohf-child' ),
			'short'    => __( 'Cushioned wedge-sole sandals with beaded straps.', 'cohf-child' ),
			'long'     => $design_note,
			'order'    => 2,
		),
		'beaded-leather-clutch'  => array(
			'name'     => __( 'Beaded Leather Clutch', 'cohf-child' ),
			'price'    => '2200',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'beaded-leather-clutch.jpg',
			'alt'      => __( 'Leather clutch bags with curved, fully beaded flaps in white and gold, multicolour, and brown, black and white bands.', 'cohf-child' ),
			'short'    => __( 'Leather clutch with a curved flap covered in handmade beadwork.', 'cohf-child' ),
			'long'     => __( 'Available in several bead patterns and in black or brown leather. Tell us the pattern you would like in the order notes and we will confirm availability before dispatch.', 'cohf-child' ),
			'order'    => 3,
		),
		'sisal-basket-red-stripe' => array(
			'name'     => __( 'Sisal Basket Bag - Red Stripe', 'cohf-child' ),
			'price'    => '1850',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'sisal-basket-red-stripe.jpg',
			'alt'      => __( 'Woven natural sisal basket bag with a red stripe, leather-wrapped handles and a leather button fastening.', 'cohf-child' ),
			'short'    => __( 'Hand-woven sisal basket with a red stripe, leather handles and a leather button closure.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each basket differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 4,
			'gallery'  => array(
				array(
					'image' => 'sisal-basket-red-stripe-2.jpg',
					'alt'   => __( 'Front view of the red-stripe sisal basket bag, showing both leather-wrapped handles and the leather button fastening.', 'cohf-child' ),
				),
			),
		),
		'sisal-tote-beaded-flap' => array(
			'name'     => __( 'Sisal Tote with Beaded Leather Flap', 'cohf-child' ),
			'price'    => '3000',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'sisal-tote-beaded-flap.jpg',
			'alt'      => __( 'Natural sisal tote with brown leather trim and shoulder straps, and a leather flap set with a round beaded disc.', 'cohf-child' ),
			'short'    => __( 'Hand-woven sisal tote with leather trim, shoulder straps and a beaded leather flap.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each tote differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 5,
			'gallery'  => array(
				array(
					'image' => 'sisal-tote-beaded-flap-2.jpg',
					'alt'   => __( 'The sisal tote hanging by its brown leather shoulder straps, showing the beaded disc on the leather flap.', 'cohf-child' ),
				),
			),
		),
		'wooden-salad-servers'   => array(
			'name'     => __( 'Wooden Salad Servers with Beaded Handles (Pair)', 'cohf-child' ),
			'price'    => '800',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'wooden-salad-servers.jpg',
			'alt'      => __( 'Hand-carved wooden salad spoons and forks tied in pairs, with beaded and patterned handle bands.', 'cohf-child' ),
			'short'    => __( 'A pair of hand-carved wooden salad servers with a beaded band on each handle. Price is per pair.', 'cohf-child' ),
			'long'     => __( 'Each pair is carved by hand, so grain, shade and beadwork vary. Add a colour preference for the beadwork in the order notes and we will do our best to match it.', 'cohf-child' ),
			'order'    => 6,
		),
		'coconut-wood-coasters'  => array(
			'name'     => __( 'Coconut Wood Coasters (Set of 4)', 'cohf-child' ),
			'price'    => '2000',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'coconut-wood-coasters.jpg',
			'alt'      => __( 'A tied stack of square coconut wood coasters with a cream inlaid band decorated with black lines and circles.', 'cohf-child' ),
			'short'    => __( 'Set of four square coconut wood coasters with a cream inlaid band, tied with raffia.', 'cohf-child' ),
			'long'     => __( 'Natural coconut wood, so the grain pattern differs on every coaster. Wipe clean with a dry or slightly damp cloth.', 'cohf-child' ),
			'order'    => 7,
		),
		'sisal-storage-basket-natural' => array(
			'name'     => __( 'Sisal Storage Basket - Natural', 'cohf-child' ),
			'price'    => '1650',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'sisal-storage-basket-natural.jpg',
			'alt'      => __( 'A round, open hand-woven sisal basket in natural golden fibre.', 'cohf-child' ),
			'short'    => __( 'Round, open hand-woven sisal basket in natural fibre. Works as a plant cover, laundry or storage basket.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each basket differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 8,
		),
		'sisal-storage-basket-two-tone' => array(
			'name'     => __( 'Sisal Storage Basket - Two-Tone', 'cohf-child' ),
			'price'    => '1850',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'sisal-storage-basket-two-tone.jpg',
			'alt'      => __( 'A round, open hand-woven sisal basket, natural at the top and dark brown at the base.', 'cohf-child' ),
			'short'    => __( 'Round, open hand-woven sisal basket, natural at the top and dark brown at the base.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each basket differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 9,
		),
		'copper-africa-wall-clock-extra-large' => array(
			'name'     => __( 'Copper Africa Wall Clock - Extra Large', 'cohf-child' ),
			'price'    => '20000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'copper-africa-wall-clock-extra-large.jpg',
			'alt'      => __( 'Extra-large copper wall clock cut in the shape of Africa, engraved with country borders and names, with raised lion and elephant figures.', 'cohf-child' ),
			'short'    => __( 'Extra-large copper wall clock in the shape of Africa, engraved with the map of the continent and set with raised lion and elephant figures.', 'cohf-child' ),
			'long'     => __( 'Battery-powered quartz movement. A statement piece for a living room, office or reception.', 'cohf-child' ),
			'order'    => 10,
		),
		'copper-africa-wall-clock-large' => array(
			'name'     => __( 'Copper Africa Wall Clock - Large', 'cohf-child' ),
			'price'    => '15000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'copper-africa-wall-clock-large.jpg',
			'alt'      => __( 'Large copper wall clock in the shape of Africa with a clock face, and raised elephant and lion scenes.', 'cohf-child' ),
			'short'    => __( 'Large copper wall clock in the shape of Africa, with raised elephant and lion scenes around the clock face.', 'cohf-child' ),
			'long'     => __( 'Battery-powered quartz movement.', 'cohf-child' ),
			'order'    => 11,
		),
		'flip-flop-gazelle' => array(
			'name'     => __( 'Flip-Flop Gazelle', 'cohf-child' ),
			'price'    => '5500',
			'category' => __( 'Flip-flop art', 'cohf-child' ),
			'image'    => 'flip-flop-gazelle.jpg',
			'alt'      => __( 'A standing gazelle sculpture carved from layered, multicoloured flip-flop rubber, with long curved brown horns.', 'cohf-child' ),
			'short'    => __( 'Standing gazelle sculpture carved from layered, colourful flip-flop rubber.', 'cohf-child' ),
			'long'     => __( 'Each sculpture is carved by hand, so colours and patterns vary from piece to piece.', 'cohf-child' ),
			'order'    => 12,
		),
		'flip-flop-warthog' => array(
			'name'     => __( 'Flip-Flop Warthog', 'cohf-child' ),
			'price'    => '1800',
			'category' => __( 'Flip-flop art', 'cohf-child' ),
			'image'    => 'flip-flop-warthog.jpg',
			'alt'      => __( 'A warthog sculpture carved from multicoloured flip-flop rubber, with white tusks and a curled tail.', 'cohf-child' ),
			'short'    => __( 'Warthog sculpture carved from colourful flip-flop rubber, complete with tusks and a curled tail.', 'cohf-child' ),
			'long'     => __( 'Each sculpture is carved by hand, so colours and patterns vary from piece to piece.', 'cohf-child' ),
			'order'    => 13,
		),
		'turtle-door-stopper' => array(
			'name'     => __( 'Turtle Door Stopper', 'cohf-child' ),
			'price'    => '700',
			'category' => __( 'Flip-flop art', 'cohf-child' ),
			'image'    => 'turtle-door-stopper.jpg',
			'alt'      => __( 'A sea turtle door stopper carved from striped red, green, blue and cream flip-flop rubber.', 'cohf-child' ),
			'short'    => __( 'Sea turtle door stopper carved from striped, colourful flip-flop rubber.', 'cohf-child' ),
			'long'     => __( 'Each piece is carved by hand, so colours and stripes vary.', 'cohf-child' ),
			'order'    => 14,
		),
		'flip-flop-lion-large' => array(
			'name'     => __( 'Flip-Flop Lion - Large', 'cohf-child' ),
			'price'    => '4800',
			'category' => __( 'Flip-flop art', 'cohf-child' ),
			'image'    => 'flip-flop-lion-large.jpg',
			'alt'      => __( 'A walking lion sculpture carved from multicoloured flip-flop rubber, with a full mane of layered rubber strips.', 'cohf-child' ),
			'short'    => __( 'Large walking lion sculpture carved from colourful flip-flop rubber, with a mane of layered rubber strips.', 'cohf-child' ),
			'long'     => __( 'Each sculpture is carved by hand, so colours and patterns vary from piece to piece.', 'cohf-child' ),
			'order'    => 15,
		),
		'flip-flop-rhino' => array(
			'name'     => __( 'Flip-Flop Rhino', 'cohf-child' ),
			'price'    => '1600',
			'category' => __( 'Flip-flop art', 'cohf-child' ),
			'image'    => 'flip-flop-rhino.jpg',
			'alt'      => __( 'A rhino sculpture carved from multicoloured flip-flop rubber in red, pink, blue and yellow.', 'cohf-child' ),
			'short'    => __( 'Rhino sculpture carved from colourful flip-flop rubber.', 'cohf-child' ),
			'long'     => __( 'Each sculpture is carved by hand, so colours and patterns vary from piece to piece.', 'cohf-child' ),
			'order'    => 16,
		),
		'ceramic-creamer-green' => array(
			'name'     => __( 'Ceramic Creamer - Green', 'cohf-child' ),
			'price'    => '1800',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'ceramic-creamer-green.jpg',
			'alt'      => __( 'A green glazed ceramic milk jug with a pouring lip and handle, decorated with a cream band of triangles, wavy lines and dots.', 'cohf-child' ),
			'short'    => __( 'Green glazed ceramic creamer jug with a hand-painted cream geometric band.', 'cohf-child' ),
			'long'     => __( 'Hand-painted, so the pattern varies slightly on each piece.', 'cohf-child' ),
			'order'    => 17,
		),
		'ebony-sugar-pot-small' => array(
			'name'     => __( 'Ebony Sugar Pot - Small', 'cohf-child' ),
			'price'    => '1500',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'ebony-sugar-pot-small.jpg',
			'alt'      => __( 'A small round dark wooden sugar pot with a knobbed lid, hand-carved with animal and leaf designs.', 'cohf-child' ),
			'short'    => __( 'Small hand-carved dark wood sugar pot with a fitted lid and carved animal and leaf designs.', 'cohf-child' ),
			'long'     => __( 'Hand-carved, so grain and carving vary on each piece.', 'cohf-child' ),
			'order'    => 18,
		),
		'banana-fibre-baobab-tree' => array(
			'name'     => __( 'Banana Fibre Baobab Tree', 'cohf-child' ),
			'price'    => '5500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'banana-fibre-baobab-tree.jpg',
			'alt'      => __( 'A baobab tree sculpture wrapped in natural banana fibre, with a thick trunk, spreading branches and a round woven base.', 'cohf-child' ),
			'short'    => __( 'Baobab tree sculpture wrapped in natural banana fibre on a woven base.', 'cohf-child' ),
			'long'     => __( 'Handmade from natural fibre, so shade and shape vary. Also works as a jewellery stand.', 'cohf-child' ),
			'order'    => 19,
		),
		'ceramic-mug-brown-blue' => array(
			'name'     => __( 'Ceramic Mug - Brown with Blue Inside', 'cohf-child' ),
			'price'    => '1300',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'ceramic-mug-brown-blue.jpg',
			'alt'      => __( 'A round-bellied glazed ceramic mug, brown outside with a deep grape-blue glaze inside and a looped handle.', 'cohf-child' ),
			'short'    => __( 'Round-bellied glazed ceramic mug, brown outside with a deep grape-blue glaze inside.', 'cohf-child' ),
			'long'     => __( 'Handmade and glazed by hand, so colour and shape vary slightly on each mug.', 'cohf-child' ),
			'order'    => 20,
		),
		'carved-africa-map-mini' => array(
			'name'     => __( 'Carved Wooden Africa Map - Mini', 'cohf-child' ),
			'price'    => '600',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'carved-africa-map-mini.jpg',
			'alt'      => __( 'A small dark wooden plaque cut in the shape of Africa, hand-carved with a lion and the word Africa.', 'cohf-child' ),
			'short'    => __( 'Small dark wooden Africa-shaped plaque, hand-carved with a lion and the word Africa.', 'cohf-child' ),
			'long'     => __( 'Hand-carved, so grain and carving vary on each piece.', 'cohf-child' ),
			'order'    => 21,
		),
		'banana-fibre-jewellery-box-oval' => array(
			'name'     => __( 'Banana Fibre Jewellery Box - Oval', 'cohf-child' ),
			'price'    => '1500',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'banana-fibre-jewellery-box-oval.jpg',
			'alt'      => __( 'An oval coiled natural-fibre box with a fitted lid, pink trim, a plaited pink handle and a knotted button.', 'cohf-child' ),
			'short'    => __( 'Oval hand-coiled natural-fibre jewellery box with a fitted lid, pink trim and a plaited handle.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each box differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 22,
		),
		'africa-map-jewellery-box' => array(
			'name'     => __( 'Africa Map Jewellery Box', 'cohf-child' ),
			'price'    => '900',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'africa-map-jewellery-box.jpg',
			'alt'      => __( 'An Africa-shaped box with a hand-painted lid showing each country in bright colours with its name.', 'cohf-child' ),
			'short'    => __( 'Africa-shaped jewellery box with a hand-painted, colourful map of the continent on the lid.', 'cohf-child' ),
			'long'     => __( 'Hand-painted, so colours and lettering vary slightly on each box.', 'cohf-child' ),
			'order'    => 23,
		),
		'banana-fibre-coasters-set-of-6' => array(
			'name'     => __( 'Banana Fibre Coasters (Set of 6)', 'cohf-child' ),
			'price'    => '800',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'banana-fibre-coasters-set-of-6.jpg',
			'alt'      => __( 'Six round woven banana fibre coasters in a checked light and dark weave, stored upright in a matching woven holder.', 'cohf-child' ),
			'short'    => __( 'Set of six hand-woven banana fibre coasters with a matching woven holder.', 'cohf-child' ),
			'long'     => __( 'Hand-woven from natural fibre, so shade and pattern vary slightly.', 'cohf-child' ),
			'order'    => 24,
		),
		'banana-fibre-coasters-kitenge-set-of-6' => array(
			'name'     => __( 'Banana Fibre Coasters with Kitenge Holder (Set of 6)', 'cohf-child' ),
			'price'    => '950',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'banana-fibre-coasters-kitenge-set-of-6.jpg',
			'alt'      => __( 'Round coasters with a printed leopard design and banana fibre rims, in a red and black checked fabric holder.', 'cohf-child' ),
			'short'    => __( 'Set of six banana fibre-rimmed coasters with printed animal designs, in a red and black checked fabric holder.', 'cohf-child' ),
			'long'     => __( 'Handmade, so designs and fabric pattern vary slightly.', 'cohf-child' ),
			'order'    => 25,
		),
		'soapstone-plate-square-jambo-africa' => array(
			'name'     => __( 'Soapstone Plate - Square (Jambo Africa)', 'cohf-child' ),
			'price'    => '900',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'soapstone-plate-square-jambo-africa.jpg',
			'alt'      => __( 'A square carved soapstone plate on a stand, painted with two Maasai figures in red shukas either side of a black map of Africa and the words Jambo Africa.', 'cohf-child' ),
			'short'    => __( 'Square carved soapstone plate painted with Maasai figures, a map of Africa and the words Jambo Africa. Stand shown for display.', 'cohf-child' ),
			'long'     => __( 'Hand-carved and hand-painted, so details vary on each piece.', 'cohf-child' ),
			'order'    => 26,
		),
		'soapstone-swan-dish-elephant' => array(
			'name'     => __( 'Soapstone Swan Dish - Elephant', 'cohf-child' ),
			'price'    => '700',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'soapstone-swan-dish-elephant.jpg',
			'alt'      => __( 'A carved soapstone dish with a raised rim, painted with an elephant and an acacia tree against a blue sky.', 'cohf-child' ),
			'short'    => __( 'Carved soapstone dish painted with an elephant and acacia tree. Works as a trinket, key or soap dish.', 'cohf-child' ),
			'long'     => __( 'Hand-carved and hand-painted, so colours vary on each piece.', 'cohf-child' ),
			'order'    => 27,
		),
		'carved-buffalo-medium' => array(
			'name'     => __( 'Carved Buffalo - Medium', 'cohf-child' ),
			'price'    => '9000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'carved-buffalo-medium.jpg',
			'alt'      => __( 'A polished dark wood carving of a walking buffalo with detailed horns and mane.', 'cohf-child' ),
			'short'    => __( 'Medium polished dark wood carving of a walking buffalo.', 'cohf-child' ),
			'long'     => __( 'Hand-carved, so grain and detail vary on each piece.', 'cohf-child' ),
			'order'    => 28,
		),
		'leather-cowskin-boho-crossbody-bag' => array(
			'name'     => __( 'Leather and Cowskin Boho Crossbody Bag', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'leather-cowskin-boho-crossbody-bag.jpg',
			'alt'      => __( 'Two round black leather crossbody bags with scalloped flaps, beaded flower motifs and natural cowhide fronts, hanging by their straps.', 'cohf-child' ),
			'short'    => __( 'Round black leather crossbody bag with a scalloped, hand-beaded flap and a natural cowhide front.', 'cohf-child' ),
			'long'     => __( 'Price is per bag. Beadwork designs and cowhide markings vary, so each bag is unique. Add the design you prefer in the order notes.', 'cohf-child' ),
			'order'    => 29,
		),
		'leather-ankara-boho-crossbody-bag' => array(
			'name'     => __( 'Leather Ankara Boho Crossbody Bag', 'cohf-child' ),
			'price'    => '1800',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'leather-ankara-boho-crossbody-bag.jpg',
			'alt'      => __( 'Two round crossbody bags with black leather flaps and bright floral Ankara fabric fronts, one yellow and one blue and purple.', 'cohf-child' ),
			'short'    => __( 'Round crossbody bag with a black leather flap and a bright floral Ankara fabric front.', 'cohf-child' ),
			'long'     => __( 'Price is per bag. Ankara prints vary. Add the colours you prefer in the order notes and we will confirm what is available.', 'cohf-child' ),
			'order'    => 30,
		),
		'leather-beaded-belt' => array(
			'name'     => __( 'Leather Beaded Belt', 'cohf-child' ),
			'price'    => '3200',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'leather-beaded-belt.jpg',
			'alt'      => __( 'A brown leather belt with a brass buckle and a red, navy and white beaded panel with triangle patterns.', 'cohf-child' ),
			'short'    => __( 'Brown leather belt with a brass buckle and a hand-beaded red, navy and white panel.', 'cohf-child' ),
			'long'     => __( 'Add your waist size in the order notes and we will confirm fit before dispatch. Beadwork colours vary.', 'cohf-child' ),
			'order'    => 31,
		),
		'assorted-fridge-magnets' => array(
			'name'     => __( 'Assorted Fridge Magnets', 'cohf-child' ),
			'price'    => '400',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'assorted-fridge-magnets.jpg',
			'alt'      => __( 'Hand-painted wooden fridge magnets shaped as African animals: giraffes, leopards, lions, elephants, rhinos, antelopes, guinea fowl and a fish.', 'cohf-child' ),
			'short'    => __( 'Hand-painted wooden animal fridge magnets. Price is per magnet.', 'cohf-child' ),
			'long'     => __( 'Choose from giraffe, leopard, lion, elephant, rhino, antelope, guinea fowl and fish designs. Add the animals you want in the order notes.', 'cohf-child' ),
			'order'    => 32,
		),
		'masai-beaded-leather-sandals' => array(
			'name'     => __( 'Maasai Beaded Leather Sandals', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'masai-beaded-leather-sandals.jpg',
			'alt'      => __( 'Black leather flat sandals with a wide strap of orange, red, yellow and green beadwork and a round beaded toe disc.', 'cohf-child' ),
			'short'    => __( 'Black leather sandals with a wide Maasai-beaded strap and a round beaded toe disc.', 'cohf-child' ),
			'long'     => __( 'Add your shoe size in the order notes and we will confirm fit before dispatch. Beadwork colours may vary slightly.', 'cohf-child' ),
			'order'    => 33,
		),
		'beaded-ghana-mask' => array(
			'name'     => __( 'Beaded Ghana Mask', 'cohf-child' ),
			'price'    => '6500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'beaded-ghana-mask.jpg',
			'alt'      => __( 'A tall black carved wooden mask decorated with coloured beadwork in blue, red, green, orange and yellow, with metal studs on the forehead and red lips.', 'cohf-child' ),
			'short'    => __( 'Tall carved wooden wall mask decorated with colourful beadwork and metal detailing.', 'cohf-child' ),
			'long'     => __( 'Hand-carved and hand-beaded, so colours and details vary on each mask.', 'cohf-child' ),
			'order'    => 34,
		),
		'painted-leather-purse-foldable' => array(
			'name'     => __( 'Painted Leather Purse - Foldable', 'cohf-child' ),
			'price'    => '900',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'painted-leather-purse-foldable.jpg',
			'alt'      => __( 'A fold-over leather purse with stitched edges, hand-painted with a lion, buffalo, elephant, rhino and leopard against a savannah scene.', 'cohf-child' ),
			'short'    => __( 'Fold-over leather purse hand-painted with the Big Five on a savannah scene.', 'cohf-child' ),
			'long'     => __( 'Hand-painted, so each purse differs slightly.', 'cohf-child' ),
			'order'    => 35,
		),
		'leather-sisal-kiondo-crossbody-bag' => array(
			'name'     => __( 'Leather and Sisal Kiondo Crossbody Bag', 'cohf-child' ),
			'price'    => '2000',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'leather-sisal-kiondo-crossbody-bag.jpg',
			'alt'      => __( 'Four round crossbody bags with brown leather flaps and woven sisal fronts in natural, black and brown stripes, each with a small metal animal or Africa emblem.', 'cohf-child' ),
			'short'    => __( 'Round crossbody bag with a brown leather flap, a woven sisal kiondo front and a small metal emblem.', 'cohf-child' ),
			'long'     => __( 'Price is per bag. Weave patterns and emblems vary. Add the design you prefer in the order notes.', 'cohf-child' ),
			'order'    => 36,
		),
		'ebony-carved-bowl-large' => array(
			'name'     => __( 'Ebony Carved Bowl - Large (6")', 'cohf-child' ),
			'price'    => '2000',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'ebony-carved-bowl-large.jpg',
			'alt'      => __( 'A round polished dark wood bowl with a smooth interior and a band of carved animal and plant designs around the outside.', 'cohf-child' ),
			'short'    => __( 'Large 6-inch polished dark wood bowl with a hand-carved band of animals around the outside.', 'cohf-child' ),
			'long'     => __( 'Hand-carved, so grain and carving vary on each bowl. Wipe clean; not dishwasher safe.', 'cohf-child' ),
			'order'    => 37,
		),
		'maasai-beaded-flag-wristband' => array(
			'name'     => __( 'Maasai Beaded Wristband', 'cohf-child' ),
			'price'    => '250',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-flag-wristband.jpg',
			'alt'      => __( 'A row of Maasai beaded wristbands in national flag designs, including Kenya, the United States, the United Kingdom and others.', 'cohf-child' ),
			'short'    => __( 'Hand-beaded Maasai wristband. Available in many national flag designs.', 'cohf-child' ),
			'long'     => __( 'Price is per wristband. Add the flag or colours you want in the order notes and we will confirm availability.', 'cohf-child' ),
			'order'    => 38,
		),
		'maasai-bead-bangle-multicolour' => array(
			'name'     => __( 'Maasai Bead Bangle - Multicolour', 'cohf-child' ),
			'price'    => '2800',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-bead-bangle-multicolour.jpg',
			'alt'      => __( 'Two wide beaded bangles: one solid orange-red outside with a zigzag interior, one in white with bold multicoloured geometric patterns.', 'cohf-child' ),
			'short'    => __( 'Wide, fully beaded Maasai bangle in bold multicoloured geometric patterns.', 'cohf-child' ),
			'long'     => __( 'Hand-beaded, so patterns vary. Add your preferred colours in the order notes.', 'cohf-child' ),
			'order'    => 39,
		),
		'banana-fibre-wall-art-maasai' => array(
			'name'     => __( 'Banana Fibre Wall Art - 8" x 16"', 'cohf-child' ),
			'price'    => '500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'banana-fibre-wall-art-maasai.jpg',
			'alt'      => __( 'A framed banana fibre collage of a Maasai warrior with spear and shield beside a woman carrying a pot, in red, black and natural tones.', 'cohf-child' ),
			'short'    => __( 'Framed 8 x 16 inch banana fibre collage of a Maasai couple in red, black and natural tones.', 'cohf-child' ),
			'long'     => __( 'Handmade from natural banana fibre, so each piece differs slightly.', 'cohf-child' ),
			'order'    => 40,
		),
		'fedora-hat-beadwrap' => array(
			'name'     => __( 'Fedora Hat with Beaded Band', 'cohf-child' ),
			'price'    => '2400',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'fedora-hat-beadwrap.jpg',
			'alt'      => __( 'A camel felt fedora hat with a wide brim and a hand-beaded band of red, mustard, black and white triangles.', 'cohf-child' ),
			'short'    => __( 'Camel felt fedora with a wide brim and a fine hand-beaded band in a triangle pattern.', 'cohf-child' ),
			'long'     => __( 'Beaded band colours vary. Add your head size or S/M/L and colour preference in the order notes and we will confirm before dispatch.', 'cohf-child' ),
			'order'    => 41,
		),
		'ebony-carved-bowl-small' => array(
			'name'     => __( 'Ebony Carved Bowl - Small (4")', 'cohf-child' ),
			'price'    => '1000',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'ebony-carved-bowl-small.jpg',
			'alt'      => __( 'A small round polished dark wood bowl with a band of carved elephant and leaf designs around the outside.', 'cohf-child' ),
			'short'    => __( 'Small 4-inch polished dark wood bowl with a hand-carved band around the outside.', 'cohf-child' ),
			'long'     => __( 'Hand-carved, so grain and carving vary on each bowl. Wipe clean; not dishwasher safe.', 'cohf-child' ),
			'order'    => 42,
		),
		'bone-salad-servers' => array(
			'name'     => __( 'Bone Salad Servers', 'cohf-child' ),
			'price'    => '400',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'bone-salad-servers.jpg',
			'alt'      => __( 'A crossed bone salad spoon and fork with black handles decorated in a white batik-style pattern.', 'cohf-child' ),
			'short'    => __( 'Bone salad spoon and fork set with black batik-patterned handles.', 'cohf-child' ),
			'long'     => __( 'Handmade, so shape and pattern vary slightly. Hand wash only.', 'cohf-child' ),
			'order'    => 43,
		),
		'mixed-beads-key-chain' => array(
			'name'     => __( 'Mixed Beads Key Chain', 'cohf-child' ),
			'price'    => '250',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'mixed-beads-key-chain.jpg',
			'alt'      => __( 'A key ring strung with bone, yellow, blue, carved and orange beads, finished with a small carved mask pendant.', 'cohf-child' ),
			'short'    => __( 'Key chain of mixed bone and coloured beads with a small carved mask pendant.', 'cohf-child' ),
			'long'     => __( 'Handmade, so bead colours and the mask carving vary.', 'cohf-child' ),
			'order'    => 44,
		),
		'raffia-ankara-basket' => array(
			'name'     => __( 'Raffia and Ankara Basket', 'cohf-child' ),
			'price'    => '2200',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'raffia-ankara-basket.jpg',
			'alt'      => __( 'A woven palm raffia tote lined and panelled with orange and black Ankara fabric, with tan leather handles and a leather button fastening.', 'cohf-child' ),
			'short'    => __( 'Hand-woven raffia tote with an Ankara fabric lining and panel, leather handles and button fastening.', 'cohf-child' ),
			'long'     => __( 'Handmade, so Ankara prints vary. Add your preferred colours in the order notes.', 'cohf-child' ),
			'order'    => 45,
		),
		'ghanaian-basket-set-sisal-hat' => array(
			'name'     => __( 'Ghanaian Basket Set with Sisal Hat', 'cohf-child' ),
			'price'    => '4500',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'ghanaian-basket-set-sisal-hat.jpg',
			'alt'      => __( 'A bright yellow woven Ghanaian basket bag with black and white leather-wrapped handles, set with a matching wide-brimmed fringed sisal hat.', 'cohf-child' ),
			'short'    => __( 'Yellow woven Ghanaian basket bag with leather-wrapped handles, plus a matching fringed sisal sun hat.', 'cohf-child' ),
			'long'     => __( 'Price is for the basket and hat together. Hand-woven, so shade and weave vary. Ask in the order notes about other colours.', 'cohf-child' ),
			'order'    => 46,
		),
		'handmade-ankara-fans' => array(
			'name'     => __( 'Handmade Ankara Fan', 'cohf-child' ),
			'price'    => '700',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'handmade-ankara-fans.jpg',
			'alt'      => __( 'Two round folding hand fans made from pleated Ankara fabric, one royal blue with yellow and red print and one dark purple with blue rings.', 'cohf-child' ),
			'short'    => __( 'Round folding hand fan made from pleated Ankara fabric.', 'cohf-child' ),
			'long'     => __( 'Price is per fan. Prints vary. Add your preferred colours in the order notes.', 'cohf-child' ),
			'order'    => 47,
		),
		'woven-ankara-bag-africa' => array(
			'name'     => __( 'Hand-Woven Ankara Bag - Africa', 'cohf-child' ),
			'price'    => '1800',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'woven-ankara-bag-africa.jpg',
			'alt'      => __( 'A red hand-woven tote with long black handles, a black map of Africa with the word Africa on the front, and woven Ankara-pattern bands at the top and base.', 'cohf-child' ),
			'short'    => __( 'Red hand-woven tote with long black handles, an Africa map motif and Ankara-pattern bands.', 'cohf-child' ),
			'long'     => __( 'Handmade, so pattern bands vary slightly.', 'cohf-child' ),
			'order'    => 48,
		),
		'safari-collection-mini-bag' => array(
			'name'     => __( 'Safari Collection Mini Bag', 'cohf-child' ),
			'price'    => '2000',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'safari-collection-mini-bag.jpg',
			'alt'      => __( 'A purple canvas mini handbag with black leather handles and trim, a beaded half-sun flap and beaded tassels.', 'cohf-child' ),
			'short'    => __( 'Purple canvas mini handbag with black leather handles, a beaded flap and beaded tassels.', 'cohf-child' ),
			'long'     => __( 'Hand-beaded, so beadwork colours vary slightly.', 'cohf-child' ),
			'order'    => 49,
		),
		'painted-leather-clutch-leopard' => array(
			'name'     => __( 'Painted Leather Clutch - Leopard', 'cohf-child' ),
			'price'    => '900',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'painted-leather-clutch-leopard.jpg',
			'alt'      => __( 'A fold-over leather clutch with stitched edges, hand-painted in a yellow leopard-spot pattern with a leopard face on the flap.', 'cohf-child' ),
			'short'    => __( 'Fold-over leather clutch hand-painted with a leopard face and leopard-spot pattern.', 'cohf-child' ),
			'long'     => __( 'Hand-painted, so each clutch differs slightly.', 'cohf-child' ),
			'order'    => 50,
		),
		'maasai-bead-bangle-clasp' => array(
			'name'     => __( 'Maasai Bead Bangle with Clasp', 'cohf-child' ),
			'price'    => '250',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-bead-bangle-clasp.jpg',
			'alt'      => __( 'A rope-style beaded bracelet in green with red, white, yellow and blue bands, finished with brass end caps and a hook clasp.', 'cohf-child' ),
			'short'    => __( 'Rope-style Maasai beaded bracelet with brass end caps and a hook clasp.', 'cohf-child' ),
			'long'     => __( 'Hand-beaded, so colours vary. Add your preferred colours in the order notes.', 'cohf-child' ),
			'order'    => 51,
		),
		'ankara-ladies-handbag' => array(
			'name'     => __( 'Handmade Ladies Ankara Handbag', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'ankara-ladies-handbag.jpg',
			'alt'      => __( 'A row of handbags in red, black, brown, beige and blue, each with a round beaded medallion and a matching small purse.', 'cohf-child' ),
			'short'    => __( 'Handmade ladies handbag with a round beaded medallion, available in red, black, brown, beige and blue.', 'cohf-child' ),
			'long'     => __( 'Add your colour choice in the order notes. Please confirm with us whether the matching small purse shown is included.', 'cohf-child' ),
			'order'    => 52,
		),
		'maasai-bead-long-necklace' => array(
			'name'     => __( 'Maasai Bead Long Necklace', 'cohf-child' ),
			'price'    => '700',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-bead-long-necklace.jpg',
			'alt'      => __( 'A statement necklace with an engraved brass crescent collar and long cascading strands of yellow beads, shown on a black display bust.', 'cohf-child' ),
			'short'    => __( 'Statement necklace with an engraved brass crescent collar and long cascading strands of beads.', 'cohf-child' ),
			'long'     => __( 'Hand-beaded, so strand length and bead colour vary slightly. Ask in the order notes about other colours.', 'cohf-child' ),
			'order'    => 53,
		),
		'maasai-bead-earrings' => array(
			'name'     => __( 'Maasai Bead Earrings', 'cohf-child' ),
			'price'    => '200',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-bead-earrings.jpg',
			'alt'      => __( 'A pair of teardrop-shaped Maasai beaded hoop earrings in rings of green, yellow, red, multicolour and black-and-white beads on silver-tone hooks.', 'cohf-child' ),
			'short'    => __( 'Pair of teardrop Maasai beaded hoop earrings on silver-tone hooks.', 'cohf-child' ),
			'long'     => __( 'Hand-beaded, so colour sequences vary. Add your preferred colours in the order notes.', 'cohf-child' ),
			'order'    => 54,
		),
		'handmade-clay-mouse' => array(
			'name'     => __( 'Handmade Clay Mouse', 'cohf-child' ),
			'price'    => '2000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'handmade-clay-mouse.jpg',
			'alt'      => __( 'A handmade grey clay mouse sitting upright with its paws together and a long curled tail, shown from six angles.', 'cohf-child' ),
			'short'    => __( 'Handmade grey clay mouse sitting upright, paws folded, with a long curling tail.', 'cohf-child' ),
			'long'     => __( 'Shaped by hand, so each mouse differs slightly in pose and finish. Price is for one mouse.', 'cohf-child' ),
			'order'    => 55,
		),
		'polymer-clay-mouse-white' => array(
			'name'     => __( 'Polymer Clay Mouse', 'cohf-child' ),
			'price'    => '3500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'polymer-clay-mouse-white.jpg',
			'alt'      => __( 'A handmade white polymer clay mouse sitting up with dark eyes, pink ears and a long pink tail curled around its body.', 'cohf-child' ),
			'short'    => __( 'Lifelike white polymer clay mouse with pink ears and a long pink tail.', 'cohf-child' ),
			'long'     => __( 'Sculpted and painted by hand, so each mouse is slightly different. Price is for one mouse.', 'cohf-child' ),
			'order'    => 56,
		),
		'ceramic-toad-lidded-jar' => array(
			'name'     => __( 'Handmade Ceramic Toad Lidded Jar', 'cohf-child' ),
			'price'    => '22500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'ceramic-toad-lidded-jar.jpg',
			'alt'      => __( 'A round stoneware jar with a hand-carved crosshatch texture and a lid topped by a detailed sculpted toad.', 'cohf-child' ),
			'short'    => __( 'Round stoneware jar with a hand-carved crosshatch body and a lid crowned by a lifelike sculpted toad.', 'cohf-child' ),
			'long'     => __( 'Thrown, carved and sculpted by hand, so no two are alike. A one-of-a-kind statement piece for a shelf, table or desk.', 'cohf-child' ),
			'order'    => 57,
		),
		'modern-thinker-man' => array(
			'name'     => __( 'Modern Thinker Man Figurine', 'cohf-child' ),
			'price'    => '1650',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'modern-thinker-man.jpg',
			'alt'      => __( 'Three abstract seated thinker figurines, two in glossy gold and one in matte white, each resting its head in its hands.', 'cohf-child' ),
			'short'    => __( 'Abstract seated thinker figurine in gold or white, a modern accent for a shelf, desk or bookcase.', 'cohf-child' ),
			'long'     => __( 'Price is for one figurine. Poses and colours vary; add your preferred colour (gold or white) and pose in the order notes.', 'cohf-child' ),
			'order'    => 58,
		),
		'beetle-sculpture' => array(
			'name'     => __( 'Beetle Sculpture', 'cohf-child' ),
			'price'    => '2200',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'beetle-sculpture.jpg',
			'alt'      => __( 'A white sculpted beetle with a smooth domed shell and finely detailed jointed legs and antennae, lying on a wooden surface.', 'cohf-child' ),
			'short'    => __( 'Handmade white beetle sculpture with a smooth domed shell and finely detailed legs.', 'cohf-child' ),
			'long'     => __( 'Shaped by hand, so each beetle differs slightly. Works on a shelf or desk, or as a wall accent. Price is for one sculpture.', 'cohf-child' ),
			'order'    => 59,
		),
		'ceramic-chameleon-sculpture' => array(
			'name'     => __( 'Ceramic Chameleon Sculpture', 'cohf-child' ),
			'price'    => '8000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'ceramic-chameleon-sculpture.jpg',
			'alt'      => __( 'A handmade stone-grey ceramic chameleon with a crested back, dotted skin and a tightly curled tail, perched on a dark round stand.', 'cohf-child' ),
			'short'    => __( 'Handmade ceramic chameleon with a crested back, textured dotted skin and a spiral tail.', 'cohf-child' ),
			'long'     => __( 'Sculpted by hand, so no two are alike. A striking statement piece for a shelf, sideboard or desk. Price is for one sculpture.', 'cohf-child' ),
			'order'    => 60,
		),
		'clay-frog-sculpture' => array(
			'name'     => __( 'Clay Frog Sculpture', 'cohf-child' ),
			'price'    => '3500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'clay-frog-sculpture.jpg',
			'alt'      => __( 'A natural clay sculpture of a round globe covered in finely detailed frogs climbing over one another, photographed in a pottery studio.', 'cohf-child' ),
			'short'    => __( 'Handmade natural clay sculpture of frogs climbing over a round globe, each one finely detailed.', 'cohf-child' ),
			'long'     => __( 'Sculpted by hand, so every piece is unique and details vary from the photo. A conversation piece for a shelf, table or garden corner under cover. Price is for one sculpture.', 'cohf-child' ),
			'order'    => 61,
		),
		'clay-traditional-cooking-pot' => array(
			'name'     => __( 'Clay Traditional Cooking Pot', 'cohf-child' ),
			'price'    => '3000',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'clay-traditional-cooking-pot.jpg',
			'alt'      => __( 'Handmade unglazed terracotta cooking pots with rounded bodies and matching lids with knob handles, set on a blue cloth.', 'cohf-child' ),
			'short'    => __( 'Handmade unglazed earthenware pot with lid, for slow cooking, serving stews and soups, or traditional kitchen decor.', 'cohf-child' ),
			'long'     => __( 'Made by hand from natural clay, so shape and colour vary slightly from pot to pot. Unglazed earthenware holds heat well for slow cooking and keeps food warm at the table. Price is for one pot with its lid.', 'cohf-child' ),
			'order'    => 62,
		),
		'traditional-clay-pot-handles' => array(
			'name'     => __( 'Traditional Clay Pot with Handles and Lid', 'cohf-child' ),
			'price'    => '3500',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'traditional-clay-pot-handles.jpg',
			'alt'      => __( 'A round terracotta clay pot with two loop handles, a woven-texture body, a patterned rim band and a fitted lid with a knob handle.', 'cohf-child' ),
			'short'    => __( 'Handmade terracotta pot with two handles, a woven-texture body and a fitted lid, for cooking, serving or kitchen decor.', 'cohf-child' ),
			'long'     => __( 'Made by hand from natural clay, so shape, texture and colour vary slightly. The handles make it easy to carry from stove to table. Price is for one pot with its lid.', 'cohf-child' ),
			'order'    => 63,
		),
		'traditional-clay-pot-dark-brown' => array(
			'name'     => __( 'Traditional Clay Pot - Dark Brown', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'traditional-clay-pot-dark-brown.jpg',
			'alt'      => __( 'A round dark brown clay pot with a flared rim and a woven-texture band around its body, on a white background.', 'cohf-child' ),
			'short'    => __( 'Handmade dark brown clay pot with a flared rim and a woven-texture band, for serving, storage or kitchen decor.', 'cohf-child' ),
			'long'     => __( 'Made by hand from natural clay, so shape, texture and colour vary slightly from pot to pot. Price is for one pot.', 'cohf-child' ),
			'order'    => 64,
		),
		'swan-planter-matte-white' => array(
			'name'     => __( 'Matte White Spatter Glaze Swan Planter', 'cohf-child' ),
			'price'    => '6500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'swan-planter-matte-white.jpg',
			'alt'      => __( 'A ceramic swan planter in a textured matte white spatter glaze, with a long curved neck, sculpted feathered wings and a smooth glazed hollow for plants.', 'cohf-child' ),
			'short'    => __( 'Ceramic swan planter in a textured matte white spatter glaze, with a graceful curved neck and sculpted wings.', 'cohf-child' ),
			'long'     => __( 'Use it for a small plant, succulents or dried flowers, or on its own as a decorative piece. Handmade, so glaze texture varies slightly. Price is for one planter.', 'cohf-child' ),
			'order'    => 65,
		),
		'ceramic-praying-frog-tea-pet' => array(
			'name'     => __( 'Ceramic Praying Frog Tea Pet', 'cohf-child' ),
			'price'    => '3500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'ceramic-praying-frog-tea-pet.jpg',
			'alt'      => __( 'A small cream ceramic frog sitting cross-legged with its hands pressed together as if praying or meditating, with dark glossy eyes and red painted stripes on its hands and feet.', 'cohf-child' ),
			'short'    => __( 'Small cream ceramic frog seated in a praying, meditating pose, made as a tea pet or a calming desk companion.', 'cohf-child' ),
			'long'     => __( 'A tea pet is a little clay figure kept on the tea tray and rinsed with leftover tea, slowly deepening its colour over time. Just as happy on a desk, shelf or windowsill. Price is for one frog.', 'cohf-child' ),
			'order'    => 66,
		),
		'beaded-gladiator-sandals' => array(
			'name'     => __( 'Beaded Gladiator Sandals', 'cohf-child' ),
			'price'    => '3500',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'beaded-gladiator-sandals.jpg',
			'alt'      => __( 'A pair of tan leather thong sandals with a high beaded ankle cuff in blocks of blue, yellow, orange and green and a cascading teardrop pattern of colourful beadwork down the foot.', 'cohf-child' ),
			'short'    => __( 'Tan leather gladiator sandals with a beaded ankle cuff and cascading teardrop beadwork in bright Maasai colours.', 'cohf-child' ),
			'long'     => __( 'Hand-beaded on leather, so colour placement varies slightly from pair to pair. Add your shoe size (EU or UK) in the order notes.', 'cohf-child' ),
			'order'    => 67,
		),
		'tribal-clay-mural-wall-painting' => array(
			'name'     => __( 'Tribal Clay Mural Wall Painting', 'cohf-child' ),
			'price'    => '2800',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'tribal-clay-mural-water-carriers.jpg',
			'alt'      => __( 'Design 1: two women carrying water pots, raised clay figures in red, orange and green on a red and black painted background in a black frame.', 'cohf-child' ),
			'short'    => __( 'Framed wall art with raised, hand-sculpted clay figures painted in bold colours. Available in five designs.', 'cohf-child' ),
			'long'     => __( 'Each mural is sculpted and painted by hand, so details vary slightly from the photos. Five designs are shown in the gallery: 1 Water carriers, 2 Drummer and dancer, 3 Dancers under the moon, 4 Market women, 5 The swing. Add the design number you want in the order notes. Price is for one framed mural.', 'cohf-child' ),
			'order'    => 68,
			'gallery'  => array(
				array(
					'image' => 'tribal-clay-mural-drummers.jpg',
					'alt'   => __( 'Design 2: a drummer and a kneeling woman with a cymbal, raised clay figures on a deep red background in a black frame.', 'cohf-child' ),
				),
				array(
					'image' => 'tribal-clay-mural-dancers.jpg',
					'alt'   => __( 'Design 3: two dancers beneath a flowering branch and full moon, raised clay figures on black with a dotted border, in a black frame.', 'cohf-child' ),
				),
				array(
					'image' => 'tribal-clay-mural-market-women.jpg',
					'alt'   => __( 'Design 4: a standing woman holding a basket aloft and a seated woman balancing a pot on her head, raised figures on an orange background.', 'cohf-child' ),
				),
				array(
					'image' => 'tribal-clay-mural-swing.jpg',
					'alt'   => __( 'Design 5: a figure on a swing hanging from a leafy branch, raised clay work on a red-orange canvas.', 'cohf-child' ),
				),
			),
		),
		'mens-leather-sandals' => array(
			'name'     => __( 'Men\'s Handmade Leather Sandals', 'cohf-child' ),
			'price'    => '3500',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'mens-leather-sandals-brown.jpg',
			'alt'      => __( 'Design 1: a pair of brown leather men\'s slide sandals with two wide crossover straps and stitched soles.', 'cohf-child' ),
			'short'    => __( 'Handmade men\'s leather sandals with stitched soles, available in five designs in black or brown leather.', 'cohf-child' ),
			'long'     => __( 'Each pair is cut and stitched by hand from genuine leather. Five designs are shown in the gallery: 1 Brown double strap, 2 Black cross strap, 3 Black toe ring, 4 Black with carved tan overlay, 5 Black toe loop. Add the design number and your shoe size (EU or UK) in the order notes and we will confirm with you before dispatch. Price is for one pair.', 'cohf-child' ),
			'order'    => 69,
			'gallery'  => array(
				array(
					'image' => 'mens-leather-sandals-black-cross.jpg',
					'alt'   => __( 'Design 2: black leather men\'s slide sandals with two wide straps crossing over the foot.', 'cohf-child' ),
				),
				array(
					'image' => 'mens-leather-sandals-toe-ring.jpg',
					'alt'   => __( 'Design 3: black leather men\'s sandals with a single wide strap and a toe ring.', 'cohf-child' ),
				),
				array(
					'image' => 'mens-leather-sandals-carved-overlay.jpg',
					'alt'   => __( 'Design 4: black leather men\'s sandals with a tan leather strap carved with a chevron pattern and a toe loop.', 'cohf-child' ),
				),
				array(
					'image' => 'mens-leather-sandals-toe-loop.jpg',
					'alt'   => __( 'Design 5: black leather men\'s toe-loop sandals, one pair worn on the feet.', 'cohf-child' ),
				),
			),
		),
		'beaded-bag-kenyan-flag' => array(
			'name'     => __( 'Beaded Kenyan Flag Handbag', 'cohf-child' ),
			'price'    => '3000',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'beaded-bag-kenyan-flag.jpg',
			'alt'      => __( 'A handbag made entirely of black, red, green and white beads in the Kenyan flag design, with the Maasai shield and crossed spears in the centre and two black handles.', 'cohf-child' ),
			'short'    => __( 'A hand-beaded handbag in the colours of the Kenyan flag, with the Maasai shield and spears at its centre. Can be customised.', 'cohf-child' ),
			'long'     => __( 'Every bead is threaded by hand, so each bag is slightly unique. Want it personalised, for example with a name or different colours? Describe what you would like in the order notes and we will confirm the details, price and timing with you before we make it. Price is for one bag in the design shown.', 'cohf-child' ),
			'order'    => 70,
		),
		'terracotta-radha-krishna-vase-medium' => array(
			'name'     => __( 'Terracotta Radha Krishna Mural Vase - Medium', 'cohf-child' ),
			'price'    => '7500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'terracotta-radha-krishna-vase.jpg',
			'alt'      => __( 'A tall hand-painted terracotta vase in green with a raised figure of Radha in blue, wearing a gold flower headdress, bangles and jewellery, beside painted yellow flowers.', 'cohf-child' ),
			'short'    => __( 'A handmade terracotta designer pot with a raised, hand-painted Radha Krishna mural. Use it as a statement flower vase or floor piece. Medium size.', 'cohf-child' ),
			'long'     => __( 'Shaped from terracotta clay, with the mural sculpted in relief and painted by hand, so colours and details vary slightly from the photo. Also available in a large size. Price is for one medium vase.', 'cohf-child' ),
			'order'    => 71,
		),
		'terracotta-radha-krishna-vase-large' => array(
			'name'     => __( 'Terracotta Radha Krishna Mural Vase - Large', 'cohf-child' ),
			'price'    => '16500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'terracotta-radha-krishna-vase.jpg',
			'alt'      => __( 'A tall hand-painted terracotta vase in green with a raised figure of Radha in blue, wearing a gold flower headdress, bangles and jewellery, beside painted yellow flowers.', 'cohf-child' ),
			'short'    => __( 'A handmade terracotta designer pot with a raised, hand-painted Radha Krishna mural. Use it as a statement flower vase or floor piece. Large size.', 'cohf-child' ),
			'long'     => __( 'Shaped from terracotta clay, with the mural sculpted in relief and painted by hand, so colours and details vary slightly from the photo. Also available in a medium size. Price is for one large vase.', 'cohf-child' ),
			'order'    => 72,
		),
		'terracotta-3d-flower-vase' => array(
			'name'     => __( 'Handcrafted 3D Terracotta Clay Flower Vase', 'cohf-child' ),
			'price'    => '8000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'terracotta-3d-flower-vase.jpg',
			'alt'      => __( 'A glossy terracotta-red clay vase decorated with raised, hand-sculpted red roses, orange sunflowers, green leaves and a trailing vine of yellow buds.', 'cohf-child' ),
			'short'    => __( 'A terracotta clay vase with hand-sculpted 3D roses, sunflowers and leaves in bold colour. A striking centrepiece with or without flowers.', 'cohf-child' ),
			'long'     => __( 'Each flower and leaf is shaped by hand from clay and painted, so details vary slightly from the photo. Price is for one vase.', 'cohf-child' ),
			'order'    => 73,
		),
		'clay-mural-dancing-couple' => array(
			'name'     => __( '3D Clay Mural Relief Wall Art - Dancing Tribal Couple', 'cohf-child' ),
			'price'    => '6000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'clay-mural-dancing-couple.jpg',
			'alt'      => __( 'A framed 3D clay relief of two tall tribal dancers in bronze and silver tones, one dancing and one holding a drum, on a dark textured background in a brown wooden frame.', 'cohf-child' ),
			'short'    => __( 'Framed 3D clay relief of a dancing tribal couple with a drum, finished in bronze and silver tones. A striking piece for a living room or entrance.', 'cohf-child' ),
			'long'     => __( 'The figures are sculpted by hand in clay, raised from the background and finished with metallic paints, so details vary slightly from the photo. Mounted in a wooden frame, ready to hang. Price is for one framed mural.', 'cohf-child' ),
			'order'    => 74,
		),
		'batik-bone-brass-cuff-bracelet' => array(
			'name'     => __( 'African Batik Cow Bone and Brass Cuff Bracelet', 'cohf-child' ),
			'price'    => '4500',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'brass-cuff-batik-bone.jpg',
			'alt'      => __( 'Design 1: a brass hand bracelet worn on the wrist, with a round black batik cow bone disc dotted in white on the back of the hand, joined to a brass ring with a matching oval bone top.', 'cohf-child' ),
			'short'    => __( 'Handmade brass jewellery set with black batik cow bone dotted in white. Three designs shown, including brass and cowrie shell styles.', 'cohf-child' ),
			'long'     => __( 'Made by hand from brass and batik-dyed cow bone, so the dot pattern and finish vary slightly from piece to piece. Three designs are shown in the gallery: 1 Batik bone hand bracelet with ring, 2 Brass bangle with cowrie shell chain and ring, 3 Hammered brass cuff with cowrie shell. Add the design number you want in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 75,
			'gallery'  => array(
				array(
					'image' => 'brass-cuff-cowrie-chain.jpg',
					'alt'   => __( 'Design 2: a brass bangle worn on the wrist with a chain of white cowrie shells running to a brass ring set with a cowrie shell.', 'cohf-child' ),
				),
				array(
					'image' => 'brass-cuff-hammered-cowrie.jpg',
					'alt'   => __( 'Design 3: a wide hammered brass cuff worn on the forearm with a cut-out set with a white cowrie shell.', 'cohf-child' ),
				),
			),
		),
		'elephant-baby-sitting-statue' => array(
			'name'     => __( 'Elephant Baby Sitting with Trunk Up Statue', 'cohf-child' ),
			'price'    => '9500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'elephant-baby-sitting-statue.jpg',
			'alt'      => __( 'A grey statue of a baby elephant sitting on its haunches with large ears spread wide and its trunk curled upwards, with finely detailed wrinkled skin.', 'cohf-child' ),
			'short'    => __( 'A charming statue of a baby elephant sitting with its trunk raised, a symbol of good luck. Finely detailed skin texture and big, friendly ears.', 'cohf-child' ),
			'long'     => __( 'A raised trunk is a traditional sign of good fortune, making this a thoughtful gift for a new home or office. Finished by hand, so shading and details vary slightly from the photo. Price is for one statue.', 'cohf-child' ),
			'order'    => 76,
		),
		'maasai-beaded-bracelets-assorted' => array(
			'name'     => __( 'Maasai Beaded Bracelets and Wristbands', 'cohf-child' ),
			'price'    => '600',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-bracelets-assorted.jpg',
			'alt'      => __( 'An assortment of flat Maasai beaded wristbands and a cuff bracelet in bold red, blue, orange, green, black and white patterns, including a white band with a row of coloured diamonds.', 'cohf-child' ),
			'short'    => __( 'Flat Maasai beaded wristbands and cuffs in bold traditional colours and patterns. Each one handmade, each one different.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand on a firm wire frame by Maasai artisans, so every band is unique and patterns vary from the photo. Tell us your preferred colours or pattern in the order notes and we will pick the closest match. Price is for one bracelet or wristband.', 'cohf-child' ),
			'order'    => 77,
		),
		'maasai-beaded-collar-necklace' => array(
			'name'     => __( 'Maasai Beaded Collar Necklace', 'cohf-child' ),
			'price'    => '4000',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-collar-necklace.jpg',
			'alt'      => __( 'A round Maasai beaded collar necklace with a bold triangle pattern in red, white, blue, green, yellow and black, a beaded front panel and long multicoloured bead fringes, fastened with a hook clasp.', 'cohf-child' ),
			'short'    => __( 'A statement Maasai collar necklace, hand-beaded in bold triangle patterns with a front panel and long colourful bead fringes.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand by Maasai artisans on a flat, flexible collar, with a hook-and-chain clasp at the back. Each collar is unique, so colours and pattern vary slightly from the photo. Price is for one necklace.', 'cohf-child' ),
			'order'    => 78,
		),
		'maasai-beaded-drop-necklace' => array(
			'name'     => __( 'Maasai Beaded Necklace', 'cohf-child' ),
			'price'    => '3500',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-drop-necklace.jpg',
			'alt'      => __( 'A Maasai beaded necklace worn at the neck: a close-fitting band of white beads with red, orange, blue, green and purple triangles, a long beaded drop panel in the same pattern, and multicoloured bead strands ending in silver discs.', 'cohf-child' ),
			'short'    => __( 'A close-fitting Maasai beaded necklace with a long drop panel of bold triangles and colourful bead strands finished with silver discs.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand by Maasai artisans, so colours and pattern vary slightly from the photo. The silver discs at the ends of the strands move and catch the light as you wear it. Price is for one necklace.', 'cohf-child' ),
			'order'    => 79,
		),
		'maasai-beaded-choker-necklace' => array(
			'name'     => __( 'Maasai Beaded Choker Necklace', 'cohf-child' ),
			'price'    => '4000',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-choker-necklace.jpg',
			'alt'      => __( 'A Maasai beaded choker in bands of orange, yellow, blue, red, white and black beads, with a deep fringe of silver chains and dangling silver discs, tied at the back with leather cords.', 'cohf-child' ),
			'short'    => __( 'A bold Maasai beaded choker with a fringe of silver chains and coin-like discs that shimmer and chime as you move.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand by Maasai artisans and finished with leather ties at the back, so it can be adjusted to fit. Each choker is unique, so colours and pattern vary slightly from the photo. Price is for one choker.', 'cohf-child' ),
			'order'    => 80,
		),
		'batik-bone-bead-necklace' => array(
			'name'     => __( 'African Batik Bone Bead Necklace', 'cohf-child' ),
			'price'    => '3500',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'batik-bone-bead-necklace.jpg',
			'alt'      => __( 'A chunky necklace of cream and black batik bone beads with dotted and swirl patterns, large carved square beads and a round brass bead at the centre, shown on a display bust.', 'cohf-child' ),
			'short'    => __( 'A chunky statement necklace of hand-dyed batik bone beads in cream and black, finished with a round brass centre bead.', 'cohf-child' ),
			'long'     => __( 'Each bone bead is carved and batik-dyed by hand, so patterns and shades vary slightly from the photo. Price is for one necklace.', 'cohf-child' ),
			'order'    => 81,
		),
		'chunky-beaded-necklace' => array(
			'name'     => __( 'Handmade African Chunky Beaded Necklace', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'chunky-beaded-necklace-black-white.jpg',
			'alt'      => __( 'Two handmade chunky necklaces of alternating black and white rectangular beads on black cords, laid out on a light cloth.', 'cohf-child' ),
			'short'    => __( 'A bold handmade necklace of chunky black and white beads on a black cord. Simple, striking and easy to wear every day.', 'cohf-child' ),
			'long'     => __( 'Strung by hand, so bead shapes and spacing vary slightly from the photo. Price is for one necklace.', 'cohf-child' ),
			'order'    => 82,
		),
		'maasai-beaded-collar-round' => array(
			'name'     => __( 'Maasai Beaded Collar Necklace - Round Multi-Ring', 'cohf-child' ),
			'price'    => '4000',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-collar-round.jpg',
			'alt'      => __( 'A large round Maasai beaded collar made of many concentric rings of orange, blue, red, green, white and black beads, held by beaded spacer bars, with a small beaded pendant at the front.', 'cohf-child' ),
			'short'    => __( 'A traditional round Maasai collar of layered bead rings in bright orange and blue, a true statement piece for celebrations.', 'cohf-child' ),
			'long'     => __( 'Made by Maasai artisans from rows of beads strung on wire and held flat by beaded spacers, in the style worn at weddings and ceremonies. Each collar is unique, so colours vary slightly from the photo. Price is for one collar.', 'cohf-child' ),
			'order'    => 83,
		),
		'batik-bone-stretch-bracelet' => array(
			'name'     => __( 'Kenyan Cow Bone Batik Stretch Bracelet', 'cohf-child' ),
			'price'    => '1000',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'batik-bone-stretch-bracelets.jpg',
			'alt'      => __( 'Handcrafted stretch bracelets held in a hand: one of chunky white and dark brown cow bone pieces, and several multi-row bracelets of black and white batik-patterned bone tubes with black beads.', 'cohf-child' ),
			'short'    => __( 'Handcrafted stretch bracelets of carved, batik-dyed Kenyan cow bone. Slips on easily and fits most wrists.', 'cohf-child' ),
			'long'     => __( 'Each bone piece is carved and batik-dyed by hand and strung on strong elastic, so patterns vary from the photo. Styles include chunky black and white pieces and multi-row patterned tubes: tell us your preferred style in the order notes and we will pick the closest match. Price is for one bracelet.', 'cohf-child' ),
			'order'    => 84,
		),
		'wooden-zebra-carving' => array(
			'name'     => __( 'Hand-Carved Wooden Zebra', 'cohf-child' ),
			'price'    => '6000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'wooden-zebra-carving.jpg',
			'alt'      => __( 'A hand-carved wooden zebra standing in profile, painted with bold black and white stripes, a black mane and black hooves.', 'cohf-child' ),
			'short'    => __( 'A hand-carved and hand-painted wooden zebra with bold black and white stripes. A classic Kenyan safari piece for a shelf or table.', 'cohf-child' ),
			'long'     => __( 'Carved from a single piece of wood and painted by hand, so stripes and shape vary slightly from the photo. Price is for one zebra.', 'cohf-child' ),
			'order'    => 85,
		),
		'maasai-beaded-sandals-gold-lace-up' => array(
			'name'     => __( 'Maasai Beaded Leather Sandals - Gold Lace-Up', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'maasai-beaded-sandals-gold-lace-up.jpg',
			'alt'      => __( 'A pair of brown leather thong sandals with a high upper covered in fine bronze and gold beadwork in a chevron pattern, tied at the ankle with brown laces.', 'cohf-child' ),
			'short'    => __( 'Brown leather thong sandals with a high, lace-up upper of fine bronze and gold Maasai beadwork. Elegant enough for an evening out.', 'cohf-child' ),
			'long'     => __( 'Hand-beaded on genuine leather, so beadwork varies slightly from pair to pair. Add your shoe size (EU or UK) in the order notes and we will confirm fit before dispatch. Price is for one pair.', 'cohf-child' ),
			'order'    => 86,
		),
		'maasai-beaded-leather-bracelet' => array(
			'name'     => __( 'Maasai Beaded Leather Bracelet', 'cohf-child' ),
			'price'    => '850',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-leather-bracelets.jpg',
			'alt'      => __( 'A row of black leather cuff bracelets with snap fasteners, each beaded in bands of blue, red, orange and green with a round beaded medallion in the centre.', 'cohf-child' ),
			'short'    => __( 'A black leather cuff with bold Maasai beadwork and a round beaded medallion, fastened with adjustable snap buttons.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand on genuine leather by Maasai artisans, with two snap positions so it fits most wrists. Colour order varies slightly from piece to piece. Price is for one bracelet.', 'cohf-child' ),
			'order'    => 87,
		),
		'maasai-beaded-choker-set' => array(
			'name'     => __( 'Maasai Beaded Choker, Wristband and Finger Ring Set', 'cohf-child' ),
			'price'    => '5000',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-choker-set.jpg',
			'alt'      => __( 'A woman wearing a matching Maasai beaded set: a choker in bands of blue, red, yellow and white, and a hand piece joining a beaded wristband to a beaded finger ring.', 'cohf-child' ),
			'short'    => __( 'A matching three-piece Maasai beaded set: choker necklace, wristband and finger ring joined by a beaded chain.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand by Maasai artisans in coordinated colours, so the pieces match each other while colours vary slightly from the photo. Price is for the full three-piece set.', 'cohf-child' ),
			'order'    => 88,
		),
		'maasai-beaded-placemat-coaster-set' => array(
			'name'     => __( 'Maasai Beaded Leather Placemat and Coaster Set', 'cohf-child' ),
			'price'    => '4500',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'maasai-beaded-placemat-coaster-set.jpg',
			'alt'      => __( 'Round beaded placemats and matching coasters in pale blue-white beads with a gold beaded pattern, each edged with braided brown leather.', 'cohf-child' ),
			'short'    => __( 'Round hand-beaded placemats with matching coasters, finished with a braided leather edge. Elegant table settings made in Kenya.', 'cohf-child' ),
			'long'     => __( 'Each piece is beaded by hand by Maasai artisans and edged with braided leather, so patterns vary slightly from the photo. Wipe clean with a damp cloth. Price is for one set of placemats with matching coasters.', 'cohf-child' ),
			'order'    => 89,
		),
		'beaded-high-heels' => array(
			'name'     => __( 'Beaded High Heels', 'cohf-child' ),
			'price'    => '5500',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'beaded-high-heels.jpg',
			'alt'      => __( 'A pair of pointed-toe block-heel shoes with ankle straps, fully covered in handmade beadwork in red, yellow, blue, green and white geometric patterns.', 'cohf-child' ),
			'short'    => __( 'Pointed-toe block heels with an ankle strap, fully covered in colourful handmade beadwork.', 'cohf-child' ),
			'long'     => __( 'Each pair is beaded by hand, so patterns and colours vary slightly from the photo. Add your shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 90,
		),
		'navy-blue-agbada' => array(
			'name'     => __( 'Navy Blue Agbada', 'cohf-child' ),
			'price'    => '13000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'navy-blue-agbada.jpg',
			'alt'      => __( 'A navy blue agbada on a mannequin: a flowing outer robe over a long tunic with tonal diamond embroidery down the front, worn with a matching navy cap embroidered in gold.', 'cohf-child' ),
			'short'    => __( 'A flowing navy blue agbada with tonal embroidery down the front, pictured with a matching gold-embroidered cap.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 91,
		),
		'men-agbada-suit-turquoise' => array(
			'name'     => __( 'Men Agbada Suit', 'cohf-child' ),
			'price'    => '13000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'men-agbada-suit-turquoise.jpg',
			'alt'      => __( 'A turquoise men\'s agbada suit on a mannequin: a flowing outer robe over a tunic with tonal embroidery at the neck and chest, matching trousers and a matching embroidered cap.', 'cohf-child' ),
			'short'    => __( 'A turquoise men\'s agbada suit with tonal embroidery at the neck and chest, pictured with matching trousers and cap.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 92,
		),
		'three-piece-agbada-mens-suit' => array(
			'name'     => __( '3-Piece Agbada Men\'s Suit', 'cohf-child' ),
			'price'    => '16500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'three-piece-agbada-mens-suit.jpg',
			'alt'      => __( 'A man wearing a navy blue three-piece agbada: a wide flowing outer robe over a long tunic with sparkling embellishment down the front, with a black cap.', 'cohf-child' ),
			'short'    => __( 'A navy blue three-piece agbada men\'s suit, with a flowing outer robe over an embellished long tunic.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 93,
		),
		'african-mens-senator-suit' => array(
			'name'     => __( 'African Men\'s Senator Suit', 'cohf-child' ),
			'price'    => '6000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'african-mens-senator-suit.jpg',
			'alt'      => __( 'A man wearing a sage green senator suit: a long-sleeved tunic with a contrasting dark collar and front placket, two chest pockets with stitched detail, and matching trousers.', 'cohf-child' ),
			'short'    => __( 'A sage green senator suit with a contrasting dark collar and front placket, two buttoned chest pockets and matching trousers.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 94,
		),
		'indo-western-sherwani-maroon' => array(
			'name'     => __( 'Indo-Western Sherwani', 'cohf-child' ),
			'price'    => '8000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'indo-western-sherwani-maroon.jpg',
			'alt'      => __( 'A maroon Indo-Western sherwani on a mannequin, in a tonal diamond-patterned fabric with a mandarin collar, an asymmetric curved front panel and gold buttons on three fabric tabs at the waist.', 'cohf-child' ),
			'short'    => __( 'A maroon Indo-Western sherwani in tonal patterned fabric, with a mandarin collar, asymmetric front and gold button tabs.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 95,
		),
		'african-kaftan-lilac' => array(
			'name'     => __( 'African Kaftan', 'cohf-child' ),
			'price'    => '5000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'african-kaftan-lilac.jpg',
			'alt'      => __( 'A man wearing a lilac short-sleeved kaftan with a round neck, an asymmetric layered hem and buttoned fabric tabs across the front, with matching trousers.', 'cohf-child' ),
			'short'    => __( 'A lilac short-sleeved kaftan with an asymmetric layered hem and buttoned tab details, worn with matching trousers.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 96,
		),
		'three-layered-casual-kaftan' => array(
			'name'     => __( 'Three-Layered Casual Kaftan', 'cohf-child' ),
			'price'    => '5000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'three-layered-casual-kaftan.jpg',
			'alt'      => __( 'A casual kaftan on a mannequin in beige textured fabric, with bold horizontal bands of deep brown and white across the chest and a small leather badge.', 'cohf-child' ),
			'short'    => __( 'A casual round-neck kaftan in beige textured fabric, with three-layered colour blocking in beige, deep brown and white.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 97,
		),
		'african-senator-suit-navy' => array(
			'name'     => __( 'African Senator Suit', 'cohf-child' ),
			'price'    => '5000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'african-senator-suit-navy.jpg',
			'alt'      => __( 'A man wearing a dark navy senator tunic with a round neck, a tonal embroidered placket down the front and matching embroidered cuffs.', 'cohf-child' ),
			'short'    => __( 'A dark navy senator suit with a tonal embroidered front placket and matching embroidered cuffs.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 98,
		),
		'maasai-dress' => array(
			'name'     => __( 'Maasai Dress', 'cohf-child' ),
			'price'    => '9000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-dress.jpg',
			'alt'      => __( 'Two long fitted dresses on mannequins, one red and one white, with three-quarter sleeves and Maasai beaded trim around the neckline, down the front, on the cuffs and in rows across the skirt.', 'cohf-child' ),
			'short'    => __( 'A long fitted dress with three-quarter sleeves, finished with colourful Maasai beadwork at the neckline, cuffs and skirt. Pictured in red and white.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your preferred colour and size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 99,
		),
		'maasai-beaded-complete-set' => array(
			'name'     => __( 'Maasai Beaded Complete Set', 'cohf-child' ),
			'price'    => '5000',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-beaded-complete-set.jpg',
			'alt'      => __( 'A matching Maasai beaded set: a beaded collar necklace with silver chains and a long beaded pendant, a pair of three-tier beaded drop earrings, and black sandals with two beaded straps in orange, green, red, white and black.', 'cohf-child' ),
			'short'    => __( 'A complete matching Maasai beaded set: collar necklace with silver chains, drop earrings and beaded flat sandals.', 'cohf-child' ),
			'long'     => __( 'Each piece is beaded by hand, so colours and patterns vary slightly from the photo. Price is for the complete set as pictured. Add your shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 100,
		),
		'maasai-beaded-jewellery-boxes' => array(
			'name'     => __( 'Maasai Beaded Jewellery Box', 'cohf-child' ),
			'price'    => '2000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'maasai-beaded-jewellery-boxes.jpg',
			'alt'      => __( 'A collection of round lidded jewellery boxes covered in Maasai beadwork, in multicolour, red, yellow, green, blue, black and gold designs with beaded swirl motifs on the lids.', 'cohf-child' ),
			'short'    => __( 'A round lidded jewellery box covered in handmade Maasai beadwork, with a beaded swirl motif on the lid.', 'cohf-child' ),
			'long'     => __( 'Price is per box. Each box is beaded by hand, so colours and patterns vary. Pictured is a selection of the designs available. Add your preferred colour in the order notes and we will confirm availability with you before dispatch.', 'cohf-child' ),
			'order'    => 101,
		),
		'maasai-beaded-sun-visor-hat' => array(
			'name'     => __( 'Maasai Beaded Sun Visor Hat', 'cohf-child' ),
			'price'    => '1800',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'maasai-beaded-sun-visor-hat.jpg',
			'alt'      => __( 'Three wide-brimmed sun visor hats in white, natural beige and red, each with a band of colourful Maasai beadwork in geometric patterns around the crown.', 'cohf-child' ),
			'short'    => __( 'A wide-brimmed sun visor with a band of colourful handmade Maasai beadwork. Pictured in white, natural beige and red.', 'cohf-child' ),
			'long'     => __( 'Price is per visor. Beadwork is done by hand, so patterns vary slightly from the photo. Add your preferred colour in the order notes and we will confirm availability with you before dispatch.', 'cohf-child' ),
			'order'    => 102,
		),
		'maasai-jewellery-set-cuff-necklace' => array(
			'name'     => __( 'Maasai Jewellery Set', 'cohf-child' ),
			'price'    => '4500',
			'category' => __( 'Jewellery', 'cohf-child' ),
			'image'    => 'maasai-jewellery-set-cuff-necklace.jpg',
			'alt'      => __( 'A Maasai jewellery set: a beaded choker with hanging silver chains and discs, a long pendant of round multicolour beaded medallions with chain fringe, and a matching black leather cuff with a large beaded medallion.', 'cohf-child' ),
			'short'    => __( 'A matching Maasai set: a beaded choker with silver chains and a long medallion pendant, plus a leather cuff with a beaded medallion.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand, so colours vary slightly from the photo. Price is for the set as pictured: necklace and cuff.', 'cohf-child' ),
			'order'    => 103,
		),
		'beaded-felt-cap-wide-brim' => array(
			'name'     => __( 'Beaded Cap', 'cohf-child' ),
			'price'    => '4500',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'beaded-felt-cap-wide-brim.jpg',
			'alt'      => __( 'Wide-brimmed felt hats in red and camel, each with a colourful Maasai beaded band around the crown and beadwork wrapped around the edge of the brim.', 'cohf-child' ),
			'short'    => __( 'A wide-brimmed felt hat with a Maasai beaded band and a beaded brim edge. Pictured in red and camel.', 'cohf-child' ),
			'long'     => __( 'Price is per hat. Beadwork is done by hand, so patterns vary slightly from the photo. Add your preferred colour in the order notes and we will confirm availability with you before dispatch.', 'cohf-child' ),
			'order'    => 104,
		),
		'maasai-dress-with-beads-lilac' => array(
			'name'     => __( 'Maasai Dress with Beads', 'cohf-child' ),
			'price'    => '5500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-dress-with-beads-lilac.jpg',
			'alt'      => __( 'A woman wearing a long fitted lilac dress decorated with a colourful Maasai beaded strip down the front and small beaded details, styled with a beaded collar, beaded cuffs and a red checked Maasai shuka worn as a cape.', 'cohf-child' ),
			'short'    => __( 'A long fitted lilac dress with a colourful Maasai beaded strip down the front and small beaded details across the skirt.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 105,
		),
		'maasai-beaded-shirt' => array(
			'name'     => __( 'Maasai Beaded Shirt', 'cohf-child' ),
			'price'    => '6500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-beaded-shirt.jpg',
			'alt'      => __( 'The back of a long-sleeved white shirt with a red and blue Maasai shuka check yoke and hem, decorated with lines of colourful beadwork and rows of hanging silver chains and discs.', 'cohf-child' ),
			'short'    => __( 'A long-sleeved white shirt with a red and blue Maasai shuka check yoke and hem, decorated with colourful beaded lines and hanging silver chains.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 106,
		),
		'maasai-beaded-shirt-red-check' => array(
			'name'     => __( 'Maasai Beaded Shirt - Red Check Trim', 'cohf-child' ),
			'price'    => '5000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-beaded-shirt-red-check.jpg',
			'alt'      => __( 'A white round-neck shirt on a mannequin with a red checked Maasai shuka placket and neckline, a curved red check pocket trim, beaded details and rows of hanging silver chains with discs.', 'cohf-child' ),
			'short'    => __( 'A white shirt with a red checked Maasai shuka placket and trim, decorated with beaded details and hanging silver chains.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 107,
		),
		'complete-custom-outfit-maasai' => array(
			'name'     => __( 'Complete Custom Outfit', 'cohf-child' ),
			'price'    => '14000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'complete-custom-outfit-maasai.jpg',
			'alt'      => __( 'A complete outfit on a mannequin: a fitted sleeveless red dress with hanging silver chains, a wide Maasai beaded collar with long coloured bead strands, and a wide black leather belt with beaded medallions and draped silver chains.', 'cohf-child' ),
			'short'    => __( 'A complete Maasai-styled outfit: fitted red dress, wide beaded collar with long bead strands, and a beaded leather belt with silver chains.', 'cohf-child' ),
			'long'     => __( 'Made to order. Beadwork is done by hand, so it varies slightly from the photo. Add your size or measurements in the order notes and we will confirm details with you before we begin.', 'cohf-child' ),
			'order'    => 108,
		),
		'maasai-beaded-dress' => array(
			'name'     => __( 'Maasai Beaded Dress', 'cohf-child' ),
			'price'    => '8500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-beaded-dress.jpg',
			'alt'      => __( 'Three fitted sleeveless dresses on mannequins, in white, maroon and white with a beaded diamond pattern, each styled with a Maasai beaded choker and a long pendant of round multicolour beaded medallions with silver chain fringe.', 'cohf-child' ),
			'short'    => __( 'A fitted sleeveless dress styled with Maasai beadwork. Pictured in white, maroon, and white with a beaded diamond pattern.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your preferred colour and size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 109,
		),
		'modern-maasai-cultural-dress' => array(
			'name'     => __( 'Modern Maasai-Inspired Cultural Dress', 'cohf-child' ),
			'price'    => '16500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'modern-maasai-cultural-dress.jpg',
			'alt'      => __( 'A woman wearing a long fitted navy dress decorated with small beads and hanging silver chains, a red beaded waistband with draped chains, a flowing red cape, a wide Maasai beaded collar and beaded cuffs.', 'cohf-child' ),
			'short'    => __( 'A modern Maasai-inspired look: long fitted navy dress with beaded details, red beaded waistband and flowing red cape.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your size in the order notes and we will confirm details, including which pieces are included, before dispatch.', 'cohf-child' ),
			'order'    => 110,
		),
		'maasai-dress-white-shuka-cape' => array(
			'name'     => __( 'Maasai Dress - White with Shuka Cape', 'cohf-child' ),
			'price'    => '8500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-dress-white-shuka-cape.jpg',
			'alt'      => __( 'A woman wearing a long fitted white dress with a colourful Maasai beaded strip down the front and small beaded details on the skirt, styled with a pink and red checked Maasai shuka cape and beaded cuffs.', 'cohf-child' ),
			'short'    => __( 'A long fitted white dress with a colourful Maasai beaded strip and small beaded details, pictured with a checked shuka cape and beaded cuffs.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your size in the order notes and we will confirm details, including which pieces are included, before dispatch.', 'cohf-child' ),
			'order'    => 111,
		),
		'maasai-mermaid-dress-red-shuka' => array(
			'name'     => __( 'Maasai Mermaid Dress - Red with Shuka Skirt', 'cohf-child' ),
			'price'    => '9000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-mermaid-dress-red-shuka.jpg',
			'alt'      => __( 'A woman wearing a long red mermaid dress with cold-shoulder checked sleeves, rows of draped gold chains across the bodice, and a flared skirt in red and purple Maasai shuka check.', 'cohf-child' ),
			'short'    => __( 'A long red mermaid dress with cold-shoulder sleeves, draped chain details and a flared Maasai shuka check skirt.', 'cohf-child' ),
			'long'     => __( 'Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 112,
		),
		'maasai-cape-dress-turquoise-purple' => array(
			'name'     => __( 'Maasai Cape Dress - Turquoise or Purple', 'cohf-child' ),
			'price'    => '9000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-cape-dress-turquoise-purple.jpg',
			'alt'      => __( 'Two women in long fitted Maasai-styled dresses: one turquoise with attached cape sleeves and a black beaded leather belt with silver chains, the other purple with a wide beaded waistband, beaded strands on the skirt and a checked shuka cape.', 'cohf-child' ),
			'short'    => __( 'A long fitted Maasai-styled dress with beaded waist detail and hanging silver chains. Pictured in turquoise with cape sleeves, and in purple with a checked shuka cape.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your preferred colour and size in the order notes and we will confirm details, including which pieces are included, before dispatch.', 'cohf-child' ),
			'order'    => 113,
		),
		'maasai-beaded-enamel-mug' => array(
			'name'     => __( 'Maasai Beaded Enamel Mug', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'maasai-beaded-enamel-mug.jpg',
			'alt'      => __( 'Four white enamel mugs with blue rims, each covered on the outside in colourful Maasai beadwork in geometric patterns of green, orange, turquoise, red, black and white.', 'cohf-child' ),
			'short'    => __( 'A white enamel mug with a blue rim, covered on the outside in colourful handmade Maasai beadwork.', 'cohf-child' ),
			'long'     => __( 'Price is per mug. Each mug is beaded by hand, so colours and patterns vary. Pictured is a selection of the designs available. Add your preferred colours in the order notes and we will confirm availability before dispatch. Wipe the beadwork clean; do not soak.', 'cohf-child' ),
			'order'    => 114,
		),
		'maasai-dress-lilac-purple-beaded-waistband' => array(
			'name'     => __( 'Maasai Dress - Lilac and Purple with Beaded Waistband', 'cohf-child' ),
			'price'    => '8500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-dress-lilac-purple-beaded-waistband.jpg',
			'alt'      => __( 'A mannequin dressed in a sleeveless lilac top and a long fitted purple skirt with a front slit, styled with a wide Maasai beaded collar with long beaded strands and a wide beaded waistband with hanging silver chains and discs.', 'cohf-child' ),
			'short'    => __( 'A Maasai-styled look: sleeveless lilac top and long fitted purple skirt with a wide beaded waistband, pictured with a beaded collar.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your size in the order notes and we will confirm details, including which pieces are included, before dispatch.', 'cohf-child' ),
			'order'    => 115,
		),
		'maasai-beaded-leather-sandals-gold' => array(
			'name'     => __( 'Maasai Beaded Leather Sandals', 'cohf-child' ),
			'price'    => '3000',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'maasai-beaded-leather-sandals-gold.jpg',
			'alt'      => __( 'A pair of brown leather slide sandals with two wide straps covered in gold beadwork with small black accents.', 'cohf-child' ),
			'short'    => __( 'Brown leather slide sandals with two straps covered in gold Maasai beadwork.', 'cohf-child' ),
			'long'     => __( 'Each pair is beaded by hand, so patterns vary slightly from the photo. Add your shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 116,
		),
		'beaded-straw-sun-hat-sandals-set' => array(
			'name'     => __( 'Beaded Straw Sun Hat and Leather Sandals Set', 'cohf-child' ),
			'price'    => '5500',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'beaded-straw-sun-hat-sandals-set.jpg',
			'alt'      => __( 'A wide-brimmed natural straw sun hat with colourful triangle beadwork around the crown and brim edge, beside a pair of brown leather toe-loop sandals with a matching beaded strap.', 'cohf-child' ),
			'short'    => __( 'A matching set: wide-brimmed straw sun hat with colourful Maasai beadwork, and brown leather sandals with a beaded strap.', 'cohf-child' ),
			'long'     => __( 'Price is for the hat and sandals together. Beadwork is done by hand, so patterns vary slightly from the photo. Add your shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 117,
		),
		'woven-african-acrylic-blanket-wrap' => array(
			'name'     => __( 'Woven African Acrylic Blanket / Wrap', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'woven-african-acrylic-blanket-wrap.jpg',
			'alt'      => __( 'A tall stack of folded woven acrylic blankets in Maasai-style checked patterns, in red, blue, green, purple and orange, each wrapped in clear plastic.', 'cohf-child' ),
			'short'    => __( 'A woven acrylic blanket in a Maasai-style check, to use as a throw or wear as a wrap. Available in several colours.', 'cohf-child' ),
			'long'     => __( 'Price is per blanket. Pictured is a selection of the colours available. Add your preferred colour in the order notes and we will confirm availability with you before dispatch.', 'cohf-child' ),
			'order'    => 118,
		),
		'maasai-beaded-cap-sandals-set' => array(
			'name'     => __( 'Maasai Beaded Cap and Sandals Set', 'cohf-child' ),
			'price'    => '5500',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'maasai-beaded-cap-sandals-set.jpg',
			'alt'      => __( 'A navy felt hat with colourful Maasai beadwork around the crown and brim edge, beside a pair of brown leather toe-loop sandals with matching beaded straps.', 'cohf-child' ),
			'short'    => __( 'A matching set: navy felt hat with a Maasai beaded band and brim, and brown leather sandals with beaded straps.', 'cohf-child' ),
			'long'     => __( 'Price is for the hat and sandals together. Beadwork is done by hand, so patterns vary slightly from the photo. Add your shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 119,
		),
		'maasai-beaded-wide-leather-belt' => array(
			'name'     => __( 'Maasai Beaded Wide Leather Belt', 'cohf-child' ),
			'price'    => '8500',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'maasai-beaded-wide-leather-belt.jpg',
			'alt'      => __( 'A wide tan leather belt with a buckle, covered in dense Maasai beadwork in bold geometric patterns of orange, white, brown, yellow, blue and green, with a fringe of silver chains and discs along the lower edge.', 'cohf-child' ),
			'short'    => __( 'A wide leather belt covered in bold handmade Maasai beadwork, finished with a fringe of silver chains and discs.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand, so patterns vary slightly from the photo. Add your waist size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 120,
		),
		'maasai-beaded-mermaid-dress-royal-blue' => array(
			'name'     => __( 'Maasai Beaded Mermaid Dress - Royal Blue', 'cohf-child' ),
			'price'    => '6500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'maasai-beaded-mermaid-dress-royal-blue.jpg',
			'alt'      => __( 'A woman wearing a long royal blue mermaid dress with thin straps, decorated with colourful Maasai beaded strands in a diamond pattern and hanging silver beaded drops, styled with a beaded collar and cuff.', 'cohf-child' ),
			'short'    => __( 'A long royal blue mermaid dress decorated with colourful Maasai beaded strands and hanging silver drops.', 'cohf-child' ),
			'long'     => __( 'Beadwork is done by hand, so it varies slightly from the photo. Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 121,
		),
		'maasai-cowrie-shell-beaded-leather-sandals' => array(
			'name'     => __( 'Maasai Cowrie Shell Beaded Leather Sandals', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'maasai-cowrie-shell-beaded-leather-sandals.jpg',
			'alt'      => __( 'A pair of dark brown leather thong sandals with stitched edges, the straps decorated with white cowrie shells set in red beadwork.', 'cohf-child' ),
			'short'    => __( 'Dark brown leather thong sandals with straps decorated in cowrie shells and red Maasai beadwork.', 'cohf-child' ),
			'long'     => __( 'Each pair is made by hand, so beadwork and shells vary slightly from the photo. Add your shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 122,
		),
		'maasai-beaded-gladiator-sandals-cowrie' => array(
			'name'     => __( 'Maasai Beaded Gladiator Sandals with Cowrie Shells', 'cohf-child' ),
			'price'    => '5500',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'maasai-beaded-gladiator-sandals-cowrie.jpg',
			'alt'      => __( 'A pair of black-soled gladiator sandals worn on the feet, with wide ankle bands and toe straps covered in multicolour, white and gold Maasai beadwork and decorated with rows of cowrie shells.', 'cohf-child' ),
			'short'    => __( 'Gladiator sandals with wide ankle bands and toe straps in colourful Maasai beadwork, decorated with cowrie shells.', 'cohf-child' ),
			'long'     => __( 'Each pair is made by hand, so beadwork and shells vary slightly from the photo. Add your shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 123,
		),
		'maasai-beaded-leather-toe-ring-sandals' => array(
			'name'     => __( 'Maasai Beaded Leather Toe-Ring Sandals', 'cohf-child' ),
			'price'    => '2500',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'maasai-beaded-leather-toe-ring-sandals.jpg',
			'alt'      => __( 'A pair of tan leather flat sandals worn on the feet, each with a wide strap beaded in pink, blue, yellow and white and a round beaded toe ring.', 'cohf-child' ),
			'short'    => __( 'Tan leather flat sandals with a wide Maasai beaded strap and a beaded toe ring, in pink, blue, yellow and white.', 'cohf-child' ),
			'long'     => __( 'Each pair is beaded by hand, so colours and patterns vary slightly from the photo. Add your shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' ),
			'order'    => 124,
		),
		'beaded-table-mat-large' => array(
			'name'     => __( 'Beaded Table Mat - Large', 'cohf-child' ),
			'price'    => '1000',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'beaded-table-mat-large.jpg',
			'alt'      => __( 'A large round beaded table mat in black and gold patterns with a gold border, held up in front of a second round mat beaded in bright multicolour.', 'cohf-child' ),
			'short'    => __( 'A large round table mat made of handmade beadwork. Pictured in black and gold, and in bright multicolour.', 'cohf-child' ),
			'long'     => __( 'Price is per mat, large size. Each mat is beaded by hand, so colours and patterns vary. Add your preferred colours in the order notes and we will confirm availability before dispatch. Wipe clean with a damp cloth.', 'cohf-child' ),
			'order'    => 125,
		),
		'maasai-beaded-keychains' => array(
			'name'     => __( 'Maasai Beaded Keychain', 'cohf-child' ),
			'price'    => '650',
			'category' => __( 'Accessories', 'cohf-child' ),
			'image'    => 'maasai-beaded-keychains.jpg',
			'alt'      => __( 'A selection of handmade beaded keychains on silver rings: rectangular designs in the Kenyan flag, the American flag and a blue pattern, and round leather-backed designs in colourful Maasai beadwork.', 'cohf-child' ),
			'short'    => __( 'A handmade Maasai beaded keychain on a silver ring. Available in flag designs and round leather-backed beadwork.', 'cohf-child' ),
			'long'     => __( 'Price is per keychain. Pictured is a selection of the designs available. Add your preferred design in the order notes and we will confirm availability before dispatch.', 'cohf-child' ),
			'order'    => 126,
		),
		'beaded-table-mat-gold-star' => array(
			'name'     => __( 'Beaded Table Mat - Gold Star', 'cohf-child' ),
			'price'    => '1000',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'beaded-table-mat-gold-star.jpg',
			'alt'      => __( 'Round table mats made entirely of gold beads, each with an open star-shaped cut-out between the centre and the outer ring.', 'cohf-child' ),
			'short'    => __( 'A round table mat beaded entirely in gold, with an open star pattern between the centre and the outer ring.', 'cohf-child' ),
			'long'     => __( 'Price is per mat. Each mat is beaded by hand, so it varies slightly from the photo. Wipe clean with a damp cloth.', 'cohf-child' ),
			'order'    => 127,
		),
		'maasai-beaded-wall-clock' => array(
			'name'     => __( 'Maasai Beaded Wall Clock', 'cohf-child' ),
			'price'    => '9000',
			'category' => __( 'Home decor', 'cohf-child' ),
			'image'    => 'maasai-beaded-wall-clock.jpg',
			'alt'      => __( 'A round wall clock with a white face and black numerals, set in a wide frame of royal blue beadwork with colourful beaded feather patterns, edged with black woven trim.', 'cohf-child' ),
			'short'    => __( 'A round wall clock framed in royal blue Maasai beadwork with colourful beaded feather patterns.', 'cohf-child' ),
			'long'     => __( 'Beaded by hand, so patterns vary slightly from the photo. Battery-powered quartz movement. We will confirm availability with you before dispatch.', 'cohf-child' ),
			'order'    => 128,
		),
		'african-print-jacket-pleated-dress-set' => array(
			'name'     => __( 'African Print Jacket and Pleated Dress Set', 'cohf-child' ),
			'price'    => '8500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'african-print-jacket-pleated-dress-set.jpg',
			'alt'      => __( 'Two outfits on mannequins: a long red pleated dress under a short African print blazer in yellow, green, red and white, and a long black pleated dress under a full-length coat in a dark green, orange and white zigzag print.', 'cohf-child' ),
			'short'    => __( 'A long pleated dress paired with an African print jacket. Pictured as a red dress with a short print blazer, and a black dress with a full-length zigzag print coat.', 'cohf-child' ),
			'long'     => __( 'Add your preferred style and size in the order notes and we will confirm details, including which pieces are included, before dispatch.', 'cohf-child' ),
			'order'    => 129,
		),
		'african-print-kitenge-dress' => array(
			'name'     => __( 'African Print Kitenge Dress', 'cohf-child' ),
			'price'    => '6500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'african-print-kitenge-dress.jpg',
			'alt'      => __( 'Two long African print dresses on mannequins: one in a maroon, orange and white geometric kitenge print, and one in maroon with a bold multicolour geometric print panel, wide sleeves and a matching head wrap.', 'cohf-child' ),
			'short'    => __( 'A long, comfortable dress in African kitenge print. Pictured in a maroon and orange geometric print, and in maroon with a multicolour print panel and matching head wrap.', 'cohf-child' ),
			'long'     => __( 'Add your preferred style and size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 130,
		),
		'african-print-boubou-dress' => array(
			'name'     => __( 'African Print Boubou Dress', 'cohf-child' ),
			'price'    => '7000',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'african-print-boubou-dress.jpg',
			'alt'      => __( 'A loose, flowing boubou-style dress on a mannequin with wide batwing sleeves, in an African print of mustard yellow, brown and red diamond shapes with white crackle lines.', 'cohf-child' ),
			'short'    => __( 'A loose, flowing boubou-style dress with wide batwing sleeves, in a mustard, brown and red African print.', 'cohf-child' ),
			'long'     => __( 'Generous, relaxed fit. Add your size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 131,
		),
		'african-print-fusion-dress' => array(
			'name'     => __( 'African Print Fusion Dress', 'cohf-child' ),
			'price'    => '5500',
			'category' => __( 'Clothing', 'cohf-child' ),
			'image'    => 'african-print-fusion-dress.jpg',
			'alt'      => __( 'Two dresses on mannequins: one with an orange, purple and teal African print peplum bodice over a long teal tulle skirt, and a fitted pale grey dress with navy and orange African print sleeves and a flared print hem.', 'cohf-child' ),
			'short'    => __( 'A dress combining plain fabric with African print. Pictured as a print peplum bodice with a teal tulle skirt, and as a fitted grey dress with print sleeves and a flared print hem.', 'cohf-child' ),
			'long'     => __( 'Add your preferred style and size in the order notes and we will confirm details and availability with you before dispatch.', 'cohf-child' ),
			'order'    => 132,
		),
	);
}

/**
 * Import a bundled shop photo into the Media Library.
 *
 * @param string $file Filename in assets/images/shop/.
 * @param string $alt  Alternative text.
 * @return int Attachment ID, or 0.
 */
function cohf_shop_import_image( $file, $alt ) {
	$path = COHF_CHILD_DIR . '/assets/images/shop/' . $file;
	if ( file_exists( $path ) === false ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_bits( $file, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$attachment_id = wp_insert_attachment( array(
		'post_mime_type' => 'image/jpeg',
		'post_title'     => sanitize_file_name( pathinfo( $file, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	), $upload['file'] );

	if ( ! $attachment_id || is_wp_error( $attachment_id ) ) {
		return 0;
	}

	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );

	return (int) $attachment_id;
}

/**
 * Create any seed product that has never been created before.
 */
function cohf_shop_seed() {
	if ( cohf_has_shop() === false || class_exists( 'WC_Product_Simple' ) === false ) {
		return;
	}
	if ( current_user_can( 'manage_woocommerce' ) === false ) {
		return;
	}

	$done  = (array) get_option( 'cohf_shop_seeded', array() );
	$seeds = cohf_shop_seed_products();

	if ( count( array_intersect( array_keys( $seeds ), $done ) ) === count( $seeds ) ) {
		return;
	}

	foreach ( $seeds as $key => $seed ) {
		if ( in_array( $key, $done, true ) ) {
			continue;
		}

		// Recorded first, so a failure part-way can never create duplicates.
		$done[] = $key;
		update_option( 'cohf_shop_seeded', $done, false );

		$term    = term_exists( $seed['category'], 'product_cat' );
		$term    = $term ? $term : wp_insert_term( $seed['category'], 'product_cat' );
		$term_id = is_array( $term ) ? (int) $term['term_id'] : 0;

		$product = new WC_Product_Simple();
		$product->set_name( $seed['name'] );
		$product->set_slug( $key );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_regular_price( $seed['price'] );
		$product->set_short_description( $seed['short'] );
		$product->set_description( $seed['long'] );
		$product->set_menu_order( (int) $seed['order'] );
		$product->set_manage_stock( false );
		$product->set_stock_status( 'instock' );
		if ( $term_id ) {
			$product->set_category_ids( array( $term_id ) );
		}

		$image_id = cohf_shop_import_image( $seed['image'], $seed['alt'] );
		if ( $image_id ) {
			$product->set_image_id( $image_id );
		}

		$gallery_ids = cohf_shop_import_gallery( $seed );
		if ( $gallery_ids ) {
			$product->set_gallery_image_ids( $gallery_ids );
		}

		$product_id = $product->save();
		if ( $product_id ) {
			update_post_meta( $product_id, '_cohf_seed_key', $key );
		}
	}
}
add_action( 'admin_init', 'cohf_shop_seed', 30 );

/**
 * One-time correction (10.0.0): version 9.99.0 seeded the grey clay mouse as
 * "Polymer Clay Mouse" at KSh 3,500. That piece is the Handmade Clay Mouse at
 * KSh 2,000. If the wrong product was created and is still unedited, turn it
 * into the Handmade Clay Mouse and mark that seed done so it is not created
 * twice. The white Polymer Clay Mouse is then seeded as a new product.
 */
function cohf_shop_fix_clay_mouse() {
	if ( cohf_has_shop() === false || function_exists( 'wc_get_product' ) === false ) {
		return;
	}
	if ( current_user_can( 'manage_woocommerce' ) === false ) {
		return;
	}
	if ( get_option( 'cohf_shop_fix_clay_mouse' ) ) {
		return;
	}
	update_option( 'cohf_shop_fix_clay_mouse', 1, false );

	$found = get_posts( array(
		'post_type'      => 'product',
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'meta_key'       => '_cohf_seed_key',
		'meta_value'     => 'polymer-clay-mouse',
		'no_found_rows'  => true,
	) );
	if ( empty( $found ) ) {
		return;
	}

	$product = wc_get_product( (int) $found[0] );
	if ( empty( $product ) || 'Polymer Clay Mouse' !== $product->get_name() ) {
		return;
	}

	$seeds = cohf_shop_seed_products();
	$seed  = $seeds['handmade-clay-mouse'];

	$product->set_name( $seed['name'] );
	$product->set_slug( 'handmade-clay-mouse' );
	$product->set_regular_price( $seed['price'] );
	$product->set_short_description( $seed['short'] );
	$product->set_description( $seed['long'] );
	$product->save();
	update_post_meta( $product->get_id(), '_cohf_seed_key', 'handmade-clay-mouse' );

	$done   = (array) get_option( 'cohf_shop_seeded', array() );
	$done[] = 'handmade-clay-mouse';
	update_option( 'cohf_shop_seeded', array_values( array_unique( $done ) ), false );
}
add_action( 'admin_init', 'cohf_shop_fix_clay_mouse', 25 );

/**
 * Import the extra photos listed for a seed product.
 *
 * @param array $seed Seed definition.
 * @return int[] Attachment IDs.
 */
function cohf_shop_import_gallery( $seed ) {
	$ids = array();
	if ( empty( $seed['gallery'] ) || is_array( $seed['gallery'] ) === false ) {
		return $ids;
	}
	foreach ( $seed['gallery'] as $photo ) {
		$id = cohf_shop_import_image( $photo['image'], $photo['alt'] );
		if ( $id ) {
			$ids[] = $id;
		}
	}
	return $ids;
}

/**
 * Add extra photos to seed products that were created before those photos
 * shipped. Runs once per product, and only when the product still has no
 * gallery, so photos the shop has chosen are never replaced.
 */
function cohf_shop_seed_galleries() {
	if ( cohf_has_shop() === false || function_exists( 'wc_get_product' ) === false ) {
		return;
	}
	if ( current_user_can( 'manage_woocommerce' ) === false ) {
		return;
	}

	$done = (array) get_option( 'cohf_shop_gallery_seeded', array() );

	foreach ( cohf_shop_seed_products() as $key => $seed ) {
		if ( empty( $seed['gallery'] ) || in_array( $key, $done, true ) ) {
			continue;
		}

		$found = get_posts( array(
			'post_type'      => 'product',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_cohf_seed_key',
			'meta_value'     => $key,
			'no_found_rows'  => true,
		) );
		if ( empty( $found ) ) {
			continue;
		}

		$done[] = $key;
		update_option( 'cohf_shop_gallery_seeded', $done, false );

		$product = wc_get_product( (int) $found[0] );
		if ( $product && empty( $product->get_gallery_image_ids() ) ) {
			$ids = cohf_shop_import_gallery( $seed );
			if ( $ids ) {
				$product->set_gallery_image_ids( $ids );
				$product->save();
			}
		}
	}
}
add_action( 'admin_init', 'cohf_shop_seed_galleries', 31 );
