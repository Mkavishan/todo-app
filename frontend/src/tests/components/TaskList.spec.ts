import {describe, it, expect, vi} from 'vitest'
import {render, fireEvent} from '@testing-library/vue'
import TaskList from '../../components/TaskList.vue'
import {createTestingPinia} from '@pinia/testing'

describe('TaskList.vue', () => {
  it('renders a list of tasks', async () => {
    const mockTasks = [
      {id: 1, title: 'Task One', description: 'Description one', completed: false},
      {id: 2, title: 'Task Two', description: 'Description two', completed: true},
    ]

    const {getByText, queryByText} = render(TaskList, {
      global: {
        plugins: [
          createTestingPinia({
            stubActions: false,
            initialState: {
              task: {
                tasks: mockTasks,
              },
            },
          }),
        ],
      },
    })

    expect(getByText('Task One')).toBeTruthy()
    expect(getByText('Description one')).toBeTruthy()
    expect(getByText('Task Two')).toBeTruthy()
    expect(queryByText('Done')).toBeTruthy()
  })

  it('calls completeTask when Done button is clicked', async () => {
    const mockTasks = [
      {id: 1, title: 'Task One', description: 'Desc', completed: false},
    ]

    const completeTask = vi.fn()

    render(TaskList, {
      global: {
        plugins: [
          createTestingPinia({
            stubActions: false,
            initialState: {
              task: {
                tasks: mockTasks,
              },
            },
            createSpy: () => completeTask,
          }),
        ],
      },
    })

    const button = document.querySelector('button')
    expect(button?.textContent).toBe('Done')

    await fireEvent.click(button!)

    expect(completeTask).toHaveBeenCalledWith(1)
  })

  it('does not show Done button for completed tasks', () => {
    const mockTasks = [
      {id: 1, title: 'Completed Task', description: 'Desc', completed: true},
    ]

    const {queryByText} = render(TaskList, {
      global: {
        plugins: [
          createTestingPinia({
            initialState: {
              task: {
                tasks: mockTasks,
              },
            },
          }),
        ],
      },
    })

    expect(queryByText('Done')).toBeNull()
  })
})
