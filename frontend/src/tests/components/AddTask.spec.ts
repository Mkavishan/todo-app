import {describe, it, expect, vi} from 'vitest'
import {render, screen, fireEvent} from '@testing-library/vue'
import AddTask from '../../components/AddTask.vue'
import {createTestingPinia} from '@pinia/testing'

describe('AddTask.vue', () => {
  it('adds a task on button click', async () => {
    const addTaskMock = vi.fn()

    render(AddTask, {
      global: {
        plugins: [
          createTestingPinia({
            stubActions: false,
            createSpy: () => addTaskMock
          })
        ]
      }
    })

    const titleInput = screen.getByPlaceholderText('Title')
    const descInput = screen.getByPlaceholderText('Description')
    const button = screen.getByRole('button', {name: 'Add'})

    await fireEvent.update(titleInput, 'New Task')
    await fireEvent.update(descInput, 'New description')
    await fireEvent.click(button)

    expect(addTaskMock).toHaveBeenCalledWith('New Task', 'New description')
  })
})
