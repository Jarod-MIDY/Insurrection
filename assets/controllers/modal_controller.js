import { Controller } from '@hotwired/stimulus'
import * as Turbo from '@hotwired/turbo'
export default class extends Controller {
	static targets = [
		'button',
		'dialog',
		'frame',
		'frameToReload',
		'content',
		'element',
		'elementAction',
	]
	currentButton
	elementObserver
	previousIndex
	connect() {
		if (!this.hasFrameTarget) {
			return
		}
		const options = {
			root: this.frameTarget,
			rootMargin: '0px',
			threshold: 0.3,
		}
		this.elementObserver = new IntersectionObserver(
			this.observeElementInstersection.bind(this),
			options,
		)
	}

	elementTargetConnected(element) {
		this.elementObserver.observe(element)
		if (
			this.elementTargets.length > 1 &&
			this.elementActionTarget.classList.contains('visually-hidden')
		) {
			this.elementActionTargets.forEach((action, index) => {
				action.classList.remove('visually-hidden')
			})
			this.updateActionAnchors(0, 1)
		}
	}

	contentTargetConnected(element) {
		if (this.contentTarget.dataset.autoClose) {
			this.close()
		}
	}

	moveToElement(event) {
		event.preventDefault()
		const target = event.currentTarget
		const element = this.elementTargets.find((element) => {
			return element.id === target.dataset.anchor
		})
		if (element) {
			element.focus()
			element.scrollIntoView()
		}
	}

	open(event) {
		event.preventDefault()
		const target = event.type !== 'click' && this.hasButtonTarget ? this.buttonTarget : event.currentTarget
		this.currentButton = target
		this.frameTarget.src = target.dataset.route
			? target.dataset.route
			: target.href
		this.frameTarget.id = target.dataset.frameId
			? target.dataset.frameId
			: this.frameTarget.id
		document.querySelector('body').classList.add('overflow-hidden')
		this.dialogTarget.showModal()
		Turbo.visit(this.frameTarget.src, {frame: 'modal'})
	}

	close(event) {
		if (event) {
			event.preventDefault()
		}
		this.currentButton.focus()
		document.querySelector('body').classList.remove('overflow-hidden')
		this.dialogTarget.classList.remove('flex')
		this.dialogTarget.close()
		this.reloadFrame()
	}

	observeElementInstersection([firstEntrie]) {
		if (!firstEntrie.isIntersecting) {
			return
		}
		if (!Number.isInteger(this.previousIndex)) {
			this.previousIndex = 0
		}
		const index = this.elementTargets.findIndex(
			(element) => element.id === firstEntrie.target.id,
		)
		let indexOffset = index - this.previousIndex
		indexOffset = indexOffset > 1 ? indexOffset : 1
		const nextIndex = index + indexOffset
		const prevIndex = index - indexOffset
		this.updateActionAnchors(prevIndex, nextIndex)
		this.previousIndex = index
	}

	updateActionAnchors(prevIndex, nextIndex) {
		const prevElement =
			prevIndex >= 0 ? this.elementTargets[prevIndex] : undefined
		const nextElement =
			nextIndex < this.elementTargets.length
				? this.elementTargets[nextIndex]
				: undefined
		if (prevElement) {
			this.elementActionTargets[0].dataset.anchor = prevElement.id
		}
		if (nextElement) {
			this.elementActionTargets[1].dataset.anchor = nextElement.id
		}
	}

	reloadFrame() {
		if (this.hasFrameToReloadTarget) {
			setTimeout(() => {
				this.frameToReloadTarget.reload()
			}, 1000)
		}
	}
}
