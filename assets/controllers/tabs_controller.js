import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
	static targets = ['tabsContainer', 'tabsList', 'tabButton', 'tabPanel']

	connect() {
		this.tabsListTarget.setAttribute('role', 'tablist')
		if (!this.tabsContainerTarget.dataset.links) {
			this.tabButtonTargets.forEach((tab, index) => {
				tab.setAttribute('role', 'tab')
				if (
					index ===
					parseInt(this.tabsContainerTarget.dataset.selected)
				) {
					tab.parentElement.classList.add('active')
					tab.setAttribute('aria-selected', 'true')
				} else {
					tab.setAttribute('tabindex', '-1')
					this.tabPanelTargets[index].setAttribute('hidden', '')
				}
			})
			this.tabPanelTargets.forEach((panel) => {
				panel.setAttribute('role', 'tabpanel')
				panel.setAttribute('tabindex', '0')
			})
		}
	}

	clickTab(e) {
		const clickedTab = e.currentTarget
		e.preventDefault()
		if (!clickedTab) return
		this.switchTab(clickedTab)
	}

	keyTab(e) {
		switch (e.key) {
			case 'ArrowLeft':
				this.moveLeft()
				break
			case 'ArrowRight':
				this.moveRight()
				break
			case 'Home':
				e.preventDefault()
				this.switchTab(this.tabButtonTargets[0])
				break
			case 'End':
				e.preventDefault()
				this.switchTab(
					this.tabButtonTargets[this.tabButtonTargets.length - 1],
				)
				break
			default:
				break
		}
	}

	switchTab(newTab) {
		const activePanelId = newTab.getAttribute('href')
		const activePanel = this.tabsContainerTarget.querySelector(activePanelId)
		
		this.tabButtonTargets.forEach((button) => {
			button.setAttribute('aria-selected', false)
			button.parentElement.classList.remove('active')
			button.setAttribute('tabindex', '-1')
		})

		this.tabPanelTargets.forEach((panel) => {
			panel.setAttribute('hidden', true)
		})

		activePanel.removeAttribute('hidden', false)

		newTab.setAttribute('aria-selected', true)
		newTab.parentElement.classList.add('active')
		newTab.setAttribute('tabindex', '0')
		newTab.focus()
	}

	moveLeft() {
		const currentTab = document.activeElement
		if (!currentTab.parentElement.previousElementSibling) {
			this.switchTab(
				this.tabButtonTargets[this.tabButtonTargets.length - 1],
			)
		} else {
			this.switchTab(
				currentTab.parentElement.previousElementSibling.querySelector(
					'a',
				),
			)
		}
	}

	moveRight() {
		const currentTab = document.activeElement
		if (!currentTab.parentElement.nextElementSibling) {
			this.switchTab(this.tabButtonTargets[0])
		} else {
			this.switchTab(
				currentTab.parentElement.nextElementSibling.querySelector('a'),
			)
		}
	}

	reloadFrame(event) {
		const frame = event.currentTarget.closest('turbo-frame')
		if (frame) {
			setTimeout(() => {
				frame.reload()
			}, 1000)
		}
	}
}
