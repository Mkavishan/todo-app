import {describe, it, expect, vi, beforeEach} from 'vitest'
import {render, screen, fireEvent} from '@testing-library/vue'
import TaskList from '../../components/TaskList.vue'
import {createTestingPinia} from '@pinia/testing'
import type {Task} from '../../types/task'

// Setup mock tasks
const mockTasks: Task[] = [
  {id: 1, title: 'Test Task 1', description: 'Desc 1', completed: false},
  {id: 2, title: 'Test Task 2', description: 'Desc 2', completed: true}
]

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
