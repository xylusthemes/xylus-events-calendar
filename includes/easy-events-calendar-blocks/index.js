( function ( blocks, element, blockEditor, components, ServerSideRender ) {

	const { createElement: el } = element;
	const { InspectorControls, useBlockProps } = blockEditor;
	const { PanelBody, SelectControl, RangeControl } = components;

	blocks.registerBlockType( 'xylus/easy-events-calendar', {
		edit( { attributes, setAttributes } ) {

			const blockProps = useBlockProps();

			const categoryData = XylusBlockData && XylusBlockData.categories ? XylusBlockData.categories : {};
			const categoryOptions = Object.entries(categoryData).map(([value, label]) => ({
				label,
				value,
			}));

			return el(
				'div',
				blockProps,
				// Sidebar Controls
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: 'Settings', initialOpen: true },

						el(RangeControl, {
							label: 'Limit',
							value: attributes.limit,
							onChange: (value) => setAttributes({ limit: value }),
							min: 1,
							max: 50
						}),

						el(SelectControl, {
							label: 'Style',
							value: attributes.style,
							options: [
								{ label: 'Style 1 (List)', value: 'style1' },
								{ label: 'Style 2 (Card Grid)', value: 'style2' },
								{ label: 'Style 3 (Modern card with top border + hover shadow)', value: 'style3' },
								{ label: 'Style 4 (Date badge + title list)', value: 'style4' },
								{ label: 'Style 5 (Horizontal card with thumbnail)', value: 'style5' },
								{ label: 'Style 6 (Grid / Masonry cards)', value: 'style6' },
								{ label: 'Style 7 (Timeline view)', value: 'style7' },
								{ label: 'Style 8 (Modern Bar)', value: 'style8' },
								{ label: 'Style 9 (Vertical Timeline)', value: 'style9' },
								{ label: 'Style 10 (Image Overlay)', value: 'style10' },
							],
							onChange: (value) => setAttributes({ style: value })
						}),

						el(SelectControl, {
							label: 'Category',
							value: attributes.category,
							options: [
								{ label: 'All Categories', value: '' },
								...categoryOptions
							],
							onChange: (value) => setAttributes({ category: value })
						})
					)
				),

				el( ServerSideRender, {
					block: 'xylus/easy-events-calendar',
					attributes
				})
			);
		},

		save() {
			return null;
		},
	} );

} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.serverSideRender
);