# Monte Carlo Estimation

How to use MonteCarlo algorithm to estimate software

## What is Monte Carlo?

Monte Carlo is a method to estimate the probability of a random variable.

When it comes to software estimation, we have a lot of factors to consider.

Instead of sending not accurate estimations, we can use Monte Carlo method to estimate on large number of tries what
result should be used.

### In that case we need to prepare csv document with columns:

- `optimistic estimate`
- `pessimistic estimate`
- `propability of pessimistic estimate`

As a final, we can use Monte Carlo method to estimate software.

## Process

1. Prepare csv document with columns:
    - `optimistic estimate`
    - `pessimistic estimate`
    - `propability of pessimistic estimate`
    - `estimate`
2. Simply run `php estimator.php`

As a result you will have a list rows saved on csv file to paste to estimation. During execution, you will see changes
in final estimate, and number of tries.