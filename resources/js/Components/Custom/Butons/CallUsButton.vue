<template>
	<a
		:href="telHref"
		:class="['inline-flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-shadow focus:outline-none focus:ring-2 focus:ring-offset-2', variantClasses, className]"
		:aria-label="ariaLabel"
		@click="$emit('click', $event)"
	>
		<span class="sr-only">{{ ariaLabel }}</span>
		<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
			<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.09 4.18 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.72c.12 1.05.38 2.07.78 3.03a2 2 0 0 1-.45 2.11L9.91 10.09a16 16 0 0 0 6 6l1.23-1.23a2 2 0 0 1 2.11-.45c.96.4 1.98.66 3.03.78A2 2 0 0 1 22 16.92z" fill="currentColor"/>
		</svg>
		<span>{{ label }}</span>
	</a>
</template>

<script>
export default {
	name: 'CallUsButton',
	emits: ['click'],
	props: {
		// Text to display inside the button
		label: { type: String, default: 'Call us' },
		// Phone number string, e.g. '+1-555-1234' or '5551234'
		phone: { type: String, required: true },
		// additional classes to append
		className: { type: [String, Array, Object], default: '' },
		// color variant: primary | neutral | danger
		variant: { type: String, default: 'primary' },
	},
	computed: {
		telHref() {
			// Ensure tel: protocol
			return this.phone.startsWith('tel:') ? this.phone : `tel:${this.phone}`
		},
		ariaLabel() {
			return `${this.label} ${this.phone}`
		},
		variantClasses() {
			switch (this.variant) {
				case 'neutral':
					return 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus:ring-gray-300'
				case 'danger':
					return 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-300'
				default:
					return 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-300'
			}
		},
	},
}
</script>

<style scoped>
.sr-only {
	position: absolute !important;
	width: 1px !important;
	height: 1px !important;
	padding: 0 !important;
	margin: -1px !important;
	overflow: hidden !important;
	clip: rect(0, 0, 0, 0) !important;
	white-space: nowrap !important;
	border: 0 !important;
}
</style>
